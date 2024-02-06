<?php

namespace App\Jobs;

use App\Models\UserDigimon;
use App\Notifications\DigimonCall;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateUserDigimonStatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            /** @var UserDigimon $userDigimonList */
            $userDigimonList = UserDigimon::where('is_dead', false)
                ->where('is_asleep', false)
                ->get();

            foreach ($userDigimonList as $userDigimon) {
                $userTimezone = $userDigimon->user->timezone ?? 'UTC';
                $currentTime = Carbon::now($userTimezone);
                $this->updateWaste($userDigimon, $currentTime);
                $this->updateHunger($userDigimon, $currentTime);
                $this->updateWeight($userDigimon, $currentTime);
                $this->updateEnergy($userDigimon, $currentTime);
                $this->updateSleep($userDigimon, $currentTime);
                $this->updateDeath($userDigimon, $currentTime);

                $userDigimon->save();
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    private function updateWaste(UserDigimon $digimon, Carbon $currentTime): void
    {
        $messIncrement = rand(10, 40) / 100;
        $newMessValue = $digimon->mess + $messIncrement;

        $newMessValue = max(0, min($newMessValue, 100));

        $digimon->mess = $newMessValue;

        if ($newMessValue >= 100) {
            if ($digimon->mess_start === null) {
                $digimon->mess_start = $currentTime;
                $digimon->user->notify(new DigimonCall($digimon->getName() . ' is dirty!'));
            } else {
                $userTimezone = $userDigimon->user->timezone ?? 'UTC';
                $messStart = Carbon::parse($digimon->mess_start, $userTimezone);
                if ($messStart->diffInHours() >= 1) {
                    $digimon->addCareMistake();
                    $digimon->mess_start = $currentTime;

                    $sicknessChance = 30;
                    if (rand(1, 100) <= $sicknessChance) {
                        $digimon->makeSick();
                    }
                }
            }
        } else {
            $digimon->mess_start = null;
        }
    }

    private function updateHunger(UserDigimon $digimon, Carbon $currentTime): void
    {
        $hungerDecrement = rand(30, 60) / 100;
        $newHungerValue = $digimon->hunger - $hungerDecrement;

        $newHungerValue = max(0, min($newHungerValue, 100));

        $digimon->hunger = $newHungerValue;

        if ($newHungerValue <= 0) {
            $userTimezone = $digimon->user->timezone ?? 'UTC';
            if ($digimon->malnutrition_start === null) {
                $digimon->malnutrition_start = $currentTime;
                $digimon->user->notify(new DigimonCall($digimon->getName() . ' is hungry!'));
            } else {
                $malnutritionStart = Carbon::parse($digimon->malnutrition_start, $userTimezone);
                if ($malnutritionStart->diffInHours() >= 1) {
                    $digimon->addCareMistake();
                    $digimon->malnutrition_start = $currentTime;
                }
            }
        } else {
            $digimon->malnutrition_start = null;
            if ($newHungerValue <= 95) {
                $digimon->consecutive_feedings = 0;
            }
        }
    }

    private function updateWeight(UserDigimon $digimon): void
    {
        $totalWeightReduction = 10;
        $totalTimeInHours = 14;
        $totalTimeInMinutes = $totalTimeInHours * 60;

        $weightReductionPerMinute = $totalWeightReduction / $totalTimeInMinutes;

        $newWeight = $digimon->weight - $weightReductionPerMinute;

        if ($newWeight < 1) {
            $digimon->weight = 1;
        } else {
            $digimon->weight = $newWeight;
        }
    }

    private function updateEnergy(UserDigimon $digimon): void
    {
        $energyRegenPerMinute = 0.1;

        $newEnergy = $digimon->energy + $energyRegenPerMinute;

        if ($newEnergy > 100) {
            $digimon->energy = 100;
        } else {
            $digimon->energy = $newEnergy;
        }
    }

    private function updateSleep(UserDigimon $digimon, Carbon $currentTime)
    {
        $userTimezone = $digimon->user->timezone ?? 'UTC';
        $sleepingHour = Carbon::parse($digimon->sleeping_hour, $userTimezone);

        if (!$digimon->is_asleep && $currentTime->diffInMinutes($sleepingHour) <= 30) {
            if ($digimon->lights_off_at && $digimon->lights_off_at->diffInMinutes($sleepingHour) <= 30) {
                $digimon->is_asleep = true;
            } else if ($currentTime >= $sleepingHour->addMinutes(30)) {
                $digimon->is_asleep = true;
                $digimon->care_mistakes += 1;
            }
        }

        if ($digimon->is_asleep && $currentTime->hour == 8 && $currentTime->minute == 0) {
            $digimon->user->notify(new DigimonCall($digimon->getName() . ' woke up!'));
            $digimon->is_asleep = false;
            $digimon->lights_off_at = null;
        }
    }

    private function updateDeath(UserDigimon $digimon, Carbon $currentTime): void
    {
        $baseMaxAgeInHours = 360; // Example value, adjust as needed
        $maxWasteTime = 24;
        $maxStarvationTime = 24;
        $maxCareMistakes = 10;

        $userTimezone = $digimon->user->timezone ?? 'UTC';

        // Age-based death
        $careMistakesFactor = 0.5 * (1 - ($digimon->care_mistakes / $maxCareMistakes));
        $maxAgeInHours = $baseMaxAgeInHours * $careMistakesFactor;
        if ($digimon->age >= $maxAgeInHours) {
            $digimon->setDead();
            $digimon->user->notify(new DigimonCall($digimon->getName() . ' died of old age.'));
            return;
        }

        // Waste death
        if ($digimon->mess >= 100) {
            $messStart = Carbon::parse($digimon->mess_start, $userTimezone);
            if ($messStart->diffInHours($currentTime) >= $maxWasteTime) {
                $digimon->setDead();
                $digimon->user->notify(new DigimonCall($digimon->getName() . ' died of bad conditions.'));
                return;
            }
        }

        // Starvation death
        if ($digimon->hunger <= 0) {
            $malnutritionStart = Carbon::parse($digimon->malnutrition_start, $userTimezone);
            if ($malnutritionStart->diffInHours($currentTime) >= $maxStarvationTime) {
                $digimon->setDead();
                $digimon->user->notify(new DigimonCall($digimon->getName() . ' died of starvation.'));
                return;
            }
        }

        // Care mistakes death
        if ($digimon->care_mistakes >= $maxCareMistakes) {
            $digimon->user->notify(new DigimonCall($digimon->getName() . ' died of bad care.'));
            $digimon->setDead();
            return;
        }
    }
}
