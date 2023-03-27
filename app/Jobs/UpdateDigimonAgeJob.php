<?php

namespace App\Jobs;

use App\Models\UserDigimon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDigimonAgeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $digimons = UserDigimon::where('is_dead', false)->get();

        /** @var UserDigimon $digimon */
        foreach ($digimons as $digimon) {
            $digimon->age += 1;
            $digimon->user->incrementAgeStat();
            $digimon->save();
        }
    }
}
