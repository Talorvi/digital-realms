<?php

namespace App\Models\Digimon\Mega;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;
use App\Models\UserDigimon;

final class BanchoMamemon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Bancho Mamemon';
        $this->stage = 6;
        $this->basePower = 176;
        $this->type = DigimonType::DATA;
    }

    public function canEvolve(UserDigimon $userDigimon): bool
    {
        return false;
    }

    public function evolve(UserDigimon $userDigimon): ?BaseDigimon
    {
        return null;
    }
}
