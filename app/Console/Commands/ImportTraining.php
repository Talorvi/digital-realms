<?php

namespace App\Console\Commands;

use App\Models\Training;
use App\Models\Training\Interface\TrainingInterface;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportTraining extends Command
{
    protected $signature = 'import:training';

    protected $description = 'Import Training from Models folder to database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filesystem = new Filesystem();

        $files = $filesystem->allFiles(app_path("Models/Training"));

        $trainingAdded = 0;
        $trainingSkipped = 0;

        foreach ($files as $file) {
            $relativePath = $file->getRelativePath();
            if ($relativePath === 'Interface') {
                continue;
            }

            $className = 'App\\Models\\Training\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
            /** @var TrainingInterface $trainingInstance */
            $trainingInstance = new $className();

            $existingTraining = Training::where('name', $trainingInstance->getName())->first();

            if (!$existingTraining) {
                Training::create([
                    'name' => $trainingInstance->getName(),
                    'hunger_reduction' => $trainingInstance->getHungerReduction(),
                    'weight_reduction' => $trainingInstance->getWeightReduction(),
                    'energy_consumption' => $trainingInstance->getEnergyConsumption(),
                ]);
                $trainingAdded++;
            } else {
                $trainingSkipped++;
            }
        }

        $this->info("{$trainingAdded} Training items imported successfully.");
        $this->info("{$trainingSkipped} Training items skipped.");
    }
}
