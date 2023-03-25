<?php

namespace App\Models\Digimon\Champion;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Ultimate\Monzaemon;
use App\Models\UserDigimon;

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
            return new Monzaemon();
        }

        return null;
    }
}
