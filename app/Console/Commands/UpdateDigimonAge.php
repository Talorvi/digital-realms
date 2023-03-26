<?php

namespace App\Console\Commands;

use App\Jobs\UpdateDigimonAgeJob;
use Illuminate\Console\Command;

class UpdateDigimonAge extends Command
{
    protected $signature = 'update:digimon-age';
    protected $description = 'Update the age of Digimon every hour if they are not dead';

    public function handle()
    {
        UpdateDigimonAgeJob::dispatch();
        $this->info('Digimon age updated successfully.');

        return 0;
    }
}
