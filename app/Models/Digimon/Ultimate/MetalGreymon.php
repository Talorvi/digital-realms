<?php

namespace App\Models\Digimon\Ultimate;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Mega\BlitzGreymon;
use App\Models\UserDigimon;

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

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        $hoursSinceCreation = $userDigimon->created_at->diffInHours();

        return $hoursSinceCreation >= 40;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        $successRate = $userDigimon->calculateEvolutionSuccessRate();

        if (rand(0, 100) < $successRate && $userDigimon->getCareMistakes() < 2) {
            return new BlitzGreymon();
        }

        return null;
    }
}
