<?php

namespace App\Console\Commands;

use App\Jobs\UpdateUserDigimonStatsJob;
use App\Models\UserDigimon;
use Illuminate\Console\Command;

class UpdateUserDigimon extends Command
{
    protected $signature = 'update:user-digimon';

    protected $description = 'Update UserDigimon game state';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        UpdateUserDigimonStatsJob::dispatch();

        $this->info('UserDigimon game state updated successfully.');
    }
}
