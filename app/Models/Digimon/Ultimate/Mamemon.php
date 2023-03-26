<?php

namespace App\Models\Digimon\Ultimate;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Mega\BanchoMamemon;
use App\Models\UserDigimon;

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

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        return $userDigimon->age >= 12 + 24 + 36 + 40;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        $successRate = $userDigimon->calculateEvolutionSuccessRate();

        if (rand(0, 100) < $successRate && $userDigimon->getCareMistakes() < 2) {
            return new BanchoMamemon();
        }

        return null;
    }
}
