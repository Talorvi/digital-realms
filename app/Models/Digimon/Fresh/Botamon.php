<?php

namespace App\Models\Digimon\Fresh;

use App\Models\Digimon\InTraining\Koromon;
use Carbon\Carbon;
use App\Models\Digimon\BaseDigimon;

final class Botamon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Botamon';
        $this->stage = 1;
    }

    public function canEvolve(): bool
    {
        $ageInMinutes = $this->getCreatedAt()->diffInMinutes(Carbon::now());
        return $ageInMinutes >= 10;
    }

    public function evolve(): ?BaseDigimon
    {
        if (!$this->canEvolve()) {
            return null;
        }

        return new Koromon();
    }
}
