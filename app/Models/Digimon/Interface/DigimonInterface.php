<?php

namespace App\Models\Digimon\Interface;

use App\Models\Digimon\BaseDigimon;
use App\Models\UserDigimon;

interface DigimonInterface
{
    public function getName(): string;
    public function getStage(): int;
    public function canEvolve(UserDigimon $userDigimon): bool;
    public function evolve(UserDigimon $userDigimon): ?BaseDigimon;
}
