<?php

namespace App\Models\Digimon;

use App\Enums\DigimonType;
use Carbon\Carbon;
use App\Models\Food\Interface\FoodInterface;
use App\Models\Training\Interface\TrainingInterface;

abstract class BaseDigimon implements Interface\DigimonInterface
{
    protected string $name = 'MissingNo';
    protected int $exp = 0;
    protected int $age = 0;
    protected int $energy = 100;
    protected int $hunger = 100;
    protected int $weight = 0;
    protected int $training = 0;
    protected int $mess = 0;
    protected bool $isAsleep = false;
    protected bool $isDead = false;
    protected int $stage = 0;
    protected int $careMistake = 0;
    protected int $basePower = 0;
    protected Carbon $created_at;
    protected DigimonType $type = DigimonType::FREE;
    protected int $battles = 0;
    protected int $battlesWon = 0;
    protected int $overfeed = 0;
    protected int $consecutiveFeeding = 0;
    protected int $feedingLimit = 5;
    protected ?Carbon $malnutritionStart = null;

    public function __construct()
    {
        $this->created_at = Carbon::now();
    }

    public function getExp(): int
    {
        return $this->exp;
    }

    public function feed(FoodInterface $food): void
    {
        $this->consecutiveFeeding++;

        if ($this->hunger <= 0) {
            if ($this->consecutiveFeeding > $this->feedingLimit) {
                $this->addOverfeed();
            }
        } else {
            $this->hunger -= $food->getHungerRegeneration();
            $this->weight += $food->getWeightAddition();
            $this->energy += $food->getEnergyRegeneration();

            if ($this->hunger <= 0) {
                $this->hunger = 0;
                $this->consecutiveFeeding = 0;
            }
        }
    }

    public function train(TrainingInterface $training)
    {
        $this->hunger += $training->getHungerReduction();
        $this->energy -= $training->getEnergyConsumption();
        $this->weight -= $training->getWeightReduction();
    }

    public function sleep(): void
    {
        $this->isAsleep = true;
    }

    public function wakeup(): void
    {
        $this->isAsleep = false;
    }

    public function clean(): void
    {
        $this->mess = 0;
    }

    public function addCareMistake(): void
    {
        $this->careMistake++;
    }

    public function getCareMistakes(): int
    {
        return $this->careMistake;
    }

    public function resetCareMistakes(): void
    {
        $this->careMistake = 0;
    }

    public function getWeight(): int
    {
        return $this->weight;
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

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getBattles(): int
    {
        return $this->battles;
    }

    public function addBattle(): void
    {
        $this->battles++;
    }

    public function getBattlesWon(): int
    {
        return $this->battlesWon;
    }

    public function addBattleWon(): void
    {
        $this->battlesWon++;
    }

    public function getOverfeed(): int
    {
        return $this->overfeed;
    }

    public function addOverfeed(): void
    {
        $this->overfeed++;
    }

    public function getTraining(): int
    {
        return $this->training;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function resetOverfeed(): void
    {
        $this->overfeed = 0;
    }

    public function addHunger(int $hunger): void
    {
        $this->hunger += $hunger;
        if ($this->hunger >= 100) {
            $this->hunger = 100;
            $this->malnutritionStart = Carbon::now();
        } else {
            $this->malnutritionStart = null;
        }
    }

    public function checkMalnutrition(): void
    {
        if ($this->malnutritionStart !== null) {
            $timeSinceMalnutritionStart = $this->malnutritionStart->diffInMinutes(Carbon::now());
            if ($timeSinceMalnutritionStart >= 60) {
                $this->addCareMistake();
                $this->malnutritionStart = Carbon::now();
            }
        }
    }

    public function isDead(): bool
    {
        return $this->isDead;
    }

    public function calculateEvolutionSuccessRate(): int
    {
        $winRate = ($this->getBattlesWon() / $this->getBattles()) * 100;

        if ($winRate >= 100) {
            return 60;
        } elseif ($winRate >= 80) {
            return 50;
        } elseif ($winRate >= 70) {
            return 40;
        } elseif ($winRate >= 40) {
            return 20;
        }

        return 0;
    }

    abstract public function canEvolve(): bool;

    abstract public function evolve(): ?BaseDigimon;
}
