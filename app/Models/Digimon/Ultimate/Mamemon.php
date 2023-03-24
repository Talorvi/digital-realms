<?php

namespace App\Models\Digimon\Ultimate;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Mega\BanchoMamemon;

final class Mamemon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Mamemon';
        $this->stage = 5;
        $this->basePower = 118;
        $this->type = DigimonType::DATA;
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
            return new BanchoMamemon();
        }

        return null;
    }
}
