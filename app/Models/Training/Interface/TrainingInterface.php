<?php

namespace App\Models\Training\Interface;

interface TrainingInterface
{
    public function getName(): string;
    public function getHungerReduction(): int;
    public function getWeightReduction(): int;
    public function getEnergyConsumption(): int;
}
