<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Ultimate\Mamemon;

final class Tyranomon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Tyranomon';
        $this->stage = 4;
        $this->basePower = 70;
        $this->type = DigimonType::DATA;
    }

    public function canEvolve(): bool
    {
        $hoursSinceCreation = $this->getCreatedAt()->diffInHours();

        return $this->getBattles() >= 15 && $hoursSinceCreation >= 36;
    }

    public function evolve(): ?BaseDigimon
    {
        if (!$this->canEvolve()) {
            return null;
        }

        $successRate = $this->calculateEvolutionSuccessRate();

        if (rand(0, 100) < $successRate) {
            return new Mamemon();
        }

        return null;
    }
}
