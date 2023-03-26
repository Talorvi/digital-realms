<?php

namespace App\Console\Commands;

use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Digimon;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportDigimon extends Command
{
    protected $signature = 'import:digimon';

    protected $description = 'Import Digimon from Models folder to database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filesystem = new Filesystem();

        $stages = ['Fresh', 'InTraining', 'Rookie', 'Champion', 'Ultimate', 'Mega'];

        $digimonAdded = 0;
        $digimonSkipped = 0;

        foreach ($stages as $stage) {
            $files = $filesystem->allFiles(app_path("Models/Digimon/{$stage}"));

            foreach ($files as $file) {
                $className = 'App\\Models\\Digimon\\' . $stage . '\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
                /** @var BaseDigimon $digimon */
                $digimon = new $className();

                $existingDigimon = Digimon::where('name', $digimon->getName())->first();

                if (!$existingDigimon) {
                    Digimon::create([
                        'name' => $digimon->getName(),
                        'stage' => $digimon->getStage(),
                        'base_power' => $digimon->getBasePower(),
                        'type' => $digimon->getType(),
                        'sleep_time' => $digimon->getSleepTime()->toTimeString(),
                        'feeding_limit' => $digimon->getFeedingLimit(),
                    ]);
                    $digimonAdded++;
                } else {
                    $digimonSkipped++;
                }
            }
        }

        $this->info("{$digimonAdded} Digimon imported successfully.");
        $this->info("{$digimonSkipped} Digimon skipped.");
    }
}
