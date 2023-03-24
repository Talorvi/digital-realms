<?php

namespace App\Models\Digimon\Mega;

use App\Enums\DigimonType;
use App\Models\Digimon\BaseDigimon;

final class BlitzGreymon extends BaseDigimon
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Blitz Greymon';
        $this->stage = 6;
        $this->basePower = 188;
        $this->type = DigimonType::VIRUS;
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
