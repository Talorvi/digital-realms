<?php

namespace App\Console\Commands;

use App\Jobs\CheckDigimonEvolutionJob;
use Illuminate\Console\Command;

class CheckDigimonEvolution extends Command
{
    protected $signature = 'check:digimon-evolution';
    protected $description = 'Check if any Digimon is ready to evolve';

    public function handle()
    {
        CheckDigimonEvolutionJob::dispatch();

        $this->info('Digimon evolution check job dispatched.');

        return 0;
    }
}
