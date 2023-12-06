<?php

namespace App\Jobs;

use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Digimon;
use App\Models\UserDigimon;
use App\Notifications\DigimonCall;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDigimonEvolutionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $userDigimons = UserDigimon::where('is_dead', false)->get();
        $digimonClassMap = UserDigimon::getDigimonClassMap();

        /** @var UserDigimon $userDigimon */
        foreach ($userDigimons as $userDigimon) {
            $digimonClass = $digimonClassMap[$userDigimon->name];
            /** @var BaseDigimon $digimonInstance */
            $digimonInstance = new $digimonClass();

            if ($digimonInstance->canEvolve($userDigimon)) {
                /** @var BaseDigimon $evolvedDigimon */
                $evolvedDigimon = $digimonInstance->evolve($userDigimon);

                if ($evolvedDigimon) {
                    $userDigimon->user->notify(new DigimonCall($userDigimon->getName() . ' digivolved into ' . $evolvedDigimon->getName() . '!'));
                    $evolvedDigimonDbId = Digimon::where('name', $evolvedDigimon->getName())->first()->id;
                    $userDigimon->name = $evolvedDigimon->getName();
                    $userDigimon->digimon_id = $evolvedDigimonDbId;
                    $userDigimon->care_mistakes = 0;
                    $userDigimon->exp = 0;
                    $userDigimon->energy = 100;
                    $userDigimon->training = 0;
                    $userDigimon->sleeping_hour = $evolvedDigimon->getSleepTime();

                    $userDigimon->user->unlockDigimon($evolvedDigimonDbId);
                } else {
                    // failed evolution
                    $userDigimon->setDead();
                    $userDigimon->user->notify(new DigimonCall($userDigimon->getName() . ' died while trying to digivolve.'));
                }
                $userDigimon->save();
            }
        }
    }

}
