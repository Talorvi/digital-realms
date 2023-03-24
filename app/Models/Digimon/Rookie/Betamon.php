<?php

namespace App\Models\Digimon\Rookie;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Champion\Airdramon;
use App\Models\Digimon\Champion\Devimon;
use App\Models\Digimon\Champion\Meramon;
use App\Models\Digimon\Champion\Numemon;
use App\Models\Digimon\Champion\Seadramon;
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

    public function canEvolve(): bool
    {
        $hoursSinceCreation = $this->getCreatedAt()->diffInHours(Carbon::now());

        return $hoursSinceCreation >= 24;
    }

    public function evolve(): ?BaseDigimon
    {
        if (!$this->canEvolve()) {
            return null;
        }

        $careMistakes = $this->getCareMistakes();
        $training = $this->getTraining();
        $overfeed = $this->getOverfeed();

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
