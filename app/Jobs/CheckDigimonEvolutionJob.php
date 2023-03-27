<?php

namespace App\Jobs;

use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Digimon;
use App\Models\UserDigimon;
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
                    $userDigimon->name = $evolvedDigimon->getName();
                    $userDigimon->digimon_id = Digimon::where('name', $evolvedDigimon->getName())->first()->id;
                    $userDigimon->care_mistakes = 0;
                    $userDigimon->exp = 0;
                    $userDigimon->energy = 100;
                    $userDigimon->training = 0;
                    $userDigimon->sleeping_hour = $evolvedDigimon->getSleepTime();
                } else {
                    // failed evolution
                    $userDigimon->is_dead = true;
                }
                $userDigimon->save();
            }
        }
    }

}
