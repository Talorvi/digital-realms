<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\UserDigimon;

final class Meramon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Meramon';
        $this->stage = 4;
        $this->basePower = 60;
        $this->type = DigimonType::DATA;
    }

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        return $userDigimon->getBattles() >= 15 && $userDigimon->age >= 12 + 24 + 36;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        $successRate = $userDigimon->calculateEvolutionSuccessRate();

        if (rand(0, 100) < $successRate) {
            return new Meramon();
        }

        return null;
    }
}
