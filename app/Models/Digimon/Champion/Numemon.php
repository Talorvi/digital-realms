<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Ultimate\Monzaemon;

final class Numemon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Numemon';
        $this->stage = 4;
        $this->basePower = 40;
        $this->type = DigimonType::VIRUS;
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
            return new Monzaemon();
        }

        return null;
    }
}
