<?php

namespace App\Models\Digimon\Mega;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;

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

    public function canEvolve(): bool
    {
        return false;
    }

    public function evolve(): ?BaseDigimon
    {
        return null;
    }
}
