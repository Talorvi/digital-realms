<?php

namespace App\Models\Food;

class Meat implements Interface\FoodInterface
{
    private string $name = 'Meat';
    private int $hunger_regeneration = 20;
    private int $weight_addition = 1;
    private int $energy_regeneration = 0;

    public function getName(): string
    {
        return $this->name;
    }

    public function getHungerRegeneration(): int
    {
        return $this->hunger_regeneration;
    }

    public function getWeightAddition(): int
    {
        return $this->weight_addition;
    }

    public function getEnergyRegeneration(): int
    {
        return $this->energy_regeneration;
    }
}
