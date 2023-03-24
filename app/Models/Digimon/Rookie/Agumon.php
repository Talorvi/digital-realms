<?php

namespace App\Models\Digimon\Rookie;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Champion\Devimon;
use App\Models\Digimon\Champion\Greymon;
use App\Models\Digimon\Champion\Meramon;
use App\Models\Digimon\Champion\Numemon;
use App\Models\Digimon\Champion\Tyranomon;
use Carbon\Carbon;

final class Agumon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Agumon';
        $this->stage = 3;
        $this->basePower = 18;
        $this->type = DigimonType::VACCINE;
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
            return new Greymon();
        } elseif ($careMistakes >= 3 && $training >= 5 && $training <= 15 && $overfeed >= 3) {
            return new Tyranomon();
        } elseif ($careMistakes <= 2 && $training >= 0 && $training <= 15) {
            return new Devimon();
        } elseif ($careMistakes >= 3 && $training >= 16 && $overfeed >= 3) {
            return new Meramon();
        } elseif ($careMistakes >= 3 && (($training >= 0 && $training <= 4) || ($training >= 5 && $overfeed <= 2))) {
            return new Numemon();
        }

        return null;
    }

}
