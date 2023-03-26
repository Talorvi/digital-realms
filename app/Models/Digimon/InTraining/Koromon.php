<?php

namespace App\Models\Digimon\InTraining;

use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Rookie\Agumon;
use App\Models\Digimon\Rookie\Betamon;
use App\Models\UserDigimon;
use Carbon\Carbon;

final class Koromon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Koromon';
        $this->stage = 2;
    }

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        return $userDigimon->age >= 12;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        $careMistakes = $userDigimon->getCareMistakes();

        if ($careMistakes >= 0 && $careMistakes <= 2) {
            return new Agumon();
        } elseif ($careMistakes >= 3) {
            return new Betamon();
        }

        return null;
    }
}
