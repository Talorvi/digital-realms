<?php

namespace App\Console\Commands;

use App\Models\Food;
use App\Models\Food\Interface\FoodInterface;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportFood extends Command
{
    protected $signature = 'import:food';

    protected $description = 'Import Food from Models folder to database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $filesystem = new Filesystem();

        $files = $filesystem->allFiles(app_path("Models/Food"));

        $foodAdded = 0;
        $foodSkipped = 0;

        foreach ($files as $file) {
            $relativePath = $file->getRelativePath();
            if ($relativePath === 'Interface') {
                continue;
            }

            $className = 'App\\Models\\Food\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
            /** @var FoodInterface $foodInstance */
            $foodInstance = new $className();

            $existingFood = Food::where('name', $foodInstance->getName())->first();

            if (!$existingFood) {
                Food::create([
                    'name' => $foodInstance->getName(),
                    'hunger_reduction' => $foodInstance->getHungerRegeneration(),
                    'weight_reduction' => $foodInstance->getWeightAddition(),
                    'energy_consumption' => $foodInstance->getEnergyRegeneration(),
                ]);
                $foodAdded++;
            } else {
                $foodSkipped++;
            }
        }

        $this->info("{$foodAdded} Food items imported successfully.");
        $this->info("{$foodSkipped} Food items skipped.");
    }
}
