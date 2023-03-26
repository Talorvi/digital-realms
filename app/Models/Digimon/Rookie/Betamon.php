<?php

namespace App\Models\Digimon\Rookie;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Champion\Airdramon;
use App\Models\Digimon\Champion\Devimon;
use App\Models\Digimon\Champion\Meramon;
use App\Models\Digimon\Champion\Numemon;
use App\Models\Digimon\Champion\Seadramon;
use App\Models\UserDigimon;
use Carbon\Carbon;

final class Betamon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Betamon';
        $this->stage = 3;
        $this->basePower = 10;
        $this->type = DigimonType::VIRUS;
    }

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        $hoursSinceCreation = $userDigimon->created_at->diffInHours(Carbon::now());

        return $hoursSinceCreation >= 24;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        $careMistakes = $userDigimon->getCareMistakes();
        $training = $userDigimon->getTraining();
        $overfeed = $userDigimon->getOverfeeds();

        if ($careMistakes <= 2 && $training >= 16) {
            return new Devimon();
        } elseif ($careMistakes <= 2 && $training >= 0 && $training <= 15) {
            return new Meramon();
        } elseif ($careMistakes >= 3 && $training >= 8 && $training <= 15 && $overfeed <= 2) {
            return new Airdramon();
        } elseif ($careMistakes >= 3 && $training >= 8 && $training <= 15 && $overfeed >= 3) {
            return new Seadramon();
        } elseif ($careMistakes >= 3 && (($training >= 0 && $training <= 7) || ($training >= 16))) {
            return new Numemon();
        }

        return null;
    }
}
