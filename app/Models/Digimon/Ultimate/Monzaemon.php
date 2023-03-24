<?php

namespace App\Models\Digimon\Ultimate;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;

final class Monzaemon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Monzaemon';
        $this->stage = 5;
        $this->basePower = 107;
        $this->type = DigimonType::VACCINE;
    }

    public function canEvolve(): bool
    {
        return false;
    }

    public function evolve(): ?BaseDigimon
    {
        return null;
    }
}
