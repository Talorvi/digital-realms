<?php

namespace App\Models\Digimon\Fresh;

use App\Models\Digimon\InTraining\Koromon;
use App\Models\UserDigimon;
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

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        $ageInMinutes = $userDigimon->created_at->diffInMinutes(Carbon::now());
        return $ageInMinutes >= 10;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        if (!$this->canEvolve($userDigimon)) {
            return null;
        }

        return new Koromon();
    }
}
