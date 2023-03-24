<?php

namespace App\Models\Food\Interface;

interface FoodInterface
{
    public function getName(): string;
    public function getHungerRegeneration(): int;
    public function getWeightAddition(): int;
    public function getEnergyRegeneration(): int;
}
