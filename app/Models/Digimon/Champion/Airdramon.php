<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Ultimate\MetalGreymon;
use App\Models\UserDigimon;

final class Airdramon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Airdramon';
        $this->stage = 4;
        $this->basePower = 55;
        $this->type = DigimonType::VACCINE;
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
            return new MetalGreymon();
        }

        return null;
    }
}
