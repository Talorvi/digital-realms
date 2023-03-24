<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Ultimate\MetalGreymon;

final class Greymon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Greymon';
        $this->stage = 4;
        $this->basePower = 75;
        $this->type = DigimonType::VACCINE;
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
            return new MetalGreymon();
        }

        return null;
    }
}
