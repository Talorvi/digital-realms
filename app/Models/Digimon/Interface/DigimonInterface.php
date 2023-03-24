<?php

namespace App\Models\Digimon\Interface;

use App\Models\Digimon\BaseDigimon;

interface DigimonInterface
{
    public function getName(): string;
    public function getStage(): int;
    public function canEvolve(): bool;
    public function evolve(): ?BaseDigimon;
}
