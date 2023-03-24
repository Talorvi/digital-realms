<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;

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
            return new Meramon();
        }

        return null;
    }
}
