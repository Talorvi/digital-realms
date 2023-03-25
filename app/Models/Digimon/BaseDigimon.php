<?php

namespace App\Models\Digimon;

use App\Enums\DigimonType;
use App\Models\UserDigimon;
use Carbon\Carbon;

abstract class BaseDigimon implements Interface\DigimonInterface
{
    protected string $name = 'MissingNo';
    protected int $stage = 0;
    protected int $basePower = 0;
    protected DigimonType $type = DigimonType::FREE;
    protected int $feedingLimit = 5;
    protected int $sleepingHour = 21;

    public function __construct()
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStage(): int
    {
        return $this->stage;
    }

    public function getBasePower(): int
    {
        return $this->basePower;
    }

    public function getSleepTime(): Carbon
    {
        return Carbon::createFromTime($this->sleepingHour, 0, 0);
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function getFeedingLimit(): int
    {
        return $this->feedingLimit;
    }

    abstract public function canEvolve(UserDigimon $userDigimon): bool;

    abstract public function evolve(UserDigimon $userDigimon): ?BaseDigimon;
}
