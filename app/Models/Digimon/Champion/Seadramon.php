<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Ultimate\Mamemon;
use App\Models\Digimon\Ultimate\MetalGreymon;
use App\Models\UserDigimon;

final class Seadramon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Seadramon';
        $this->stage = 4;
        $this->basePower = 50;
        $this->type = DigimonType::DATA;
    }

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        $hoursSinceCreation = $userDigimon->created_at->diffInHours();

        return $userDigimon->getBattles() >= 15 && $hoursSinceCreation >= 36;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        $successRate = $userDigimon->calculateEvolutionSuccessRate();

        if (rand(0, 100) < $successRate) {
            return new Mamemon();
        }

        return null;
    }
}
