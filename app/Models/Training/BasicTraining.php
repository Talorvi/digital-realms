<?php

namespace App\Models\Training;

class BasicTraining implements Interface\TrainingInterface
{
    private string $name = 'Basic Training';
    private int $hunger_reduction = 2;
    private int $weight_reduction = 1;
    private int $energy_consumption = 20;
    public function getName(): string
    {
        return $this->name;
    }

    public function getHungerReduction(): int
    {
        return $this->hunger_reduction;
    }

    public function getWeightReduction(): int
    {
        return $this->weight_reduction;
    }

    public function getEnergyConsumption(): int
    {
        return $this->energy_consumption;
    }
}
