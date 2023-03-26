<?php

namespace App\Jobs;

use App\Models\UserDigimon;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserDigimonStatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        /** @var UserDigimon $userDigimonList */
        $userDigimonList = UserDigimon::where('is_dead', false)
            ->where('is_asleep', false)
            ->get();

        foreach ($userDigimonList as $userDigimon) {
            $this->updateWaste($userDigimon);
            $this->updateHunger($userDigimon);
            $this->updateWeight($userDigimon);
            $this->updateEnergy($userDigimon);
            $this->updateSleep($userDigimon);

            $userDigimon->save();
        }
    }

    private function updateWaste(UserDigimon $digimon): void
    {
        $messIncrement = rand(40, 70) / 100;
        $newMessValue = $digimon->mess + $messIncrement;

        $newMessValue = max(0, min($newMessValue, 100));

        $digimon->mess = $newMessValue;

        if ($newMessValue >= 100) {
            if ($digimon->mess_start === null) {
                $digimon->mess_start = now();
            } else {
                $messStart = Carbon::parse($digimon->mess_start);
                if ($messStart->diffInHours() >= 1) {
                    $digimon->addCareMistake();
                    $digimon->mess_start = now();
                }
            }
        } else {
            $digimon->mess_start = null;
        }
    }

    private function updateHunger(UserDigimon $digimon): void
    {
        $hungerDecrement = rand(30, 60) / 100;
        $newHungerValue = $digimon->hunger - $hungerDecrement;

        $newHungerValue = max(0, min($newHungerValue, 100));

        $digimon->hunger = $newHungerValue;

        if ($newHungerValue <= 0) {
            if ($digimon->malnutrition_start === null) {
                $digimon->malnutrition_start = now();
            } else {
                $malnutritionStart = Carbon::parse($digimon->malnutrition_start);
                if ($malnutritionStart->diffInHours() >= 1) {
                    $digimon->addCareMistake();
                    $digimon->malnutrition_start = now();
                }
            }
        } else {
            $digimon->malnutrition_start = null;
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

    private function updateSleep(UserDigimon $digimon)
    {
        $currentTime = Carbon::now();
        $sleepingHour = $digimon->sleeping_hour;

        if (!$digimon->is_asleep && $currentTime->diffInMinutes($sleepingHour) <= 30) {
            if ($digimon->lights_off_at && $digimon->lights_off_at->diffInMinutes($sleepingHour) <= 30) {
                $digimon->is_asleep = true;
            } else if ($currentTime >= $sleepingHour->addMinutes(30)) {
                $digimon->is_asleep = true;
                $digimon->care_mistakes += 1;
            }
        }
    }
}
