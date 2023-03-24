<?php

namespace App\Models\Digimon\Ultimate;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Mega\BlitzGreymon;

final class MetalGreymon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Metal Greymon';
        $this->stage = 5;
        $this->basePower = 126;
        $this->type = DigimonType::VIRUS;
    }

    public function canEvolve(): bool
    {
        $hoursSinceCreation = $this->getCreatedAt()->diffInHours();

        return $hoursSinceCreation >= 40;
    }

    public function evolve(): ?BaseDigimon
    {
        if (!$this->canEvolve()) {
            return null;
        }

        $successRate = $this->calculateEvolutionSuccessRate();

        if (rand(0, 100) < $successRate && $this->getCareMistakes() < 2) {
            return new BlitzGreymon();
        }

        return null;
    }
}
