<?php

namespace App\Models\Digimon\InTraining;

use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Rookie\Agumon;
use App\Models\Digimon\Rookie\Betamon;

final class Koromon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Koromon';
        $this->stage = 2;
    }

    public function canEvolve(): bool
    {
        $ageInHours = $this->getAgeInHours();

        if ($ageInHours >= 12) {
            return true;
        }

        return false;
    }

    public function evolve(): ?BaseDigimon
    {
        if (!$this->canEvolve()) {
            return null;
        }

        $careMistakes = $this->getCareMistakes();

        if ($careMistakes >= 0 && $careMistakes <= 2) {
            return new Agumon();
        } elseif ($careMistakes >= 3) {
            return new Betamon();
        }

        return null;
    }

    private function getAgeInHours(): float
    {
        $ageInSeconds = time() - $this->created_at->getTimestamp();
        return $ageInSeconds / 3600;
    }
}
