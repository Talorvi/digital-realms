<?php

namespace App\Models\Battles;

use App\Models\UserDigimon;

class SingleBattle
{
    private UserDigimon $player1Digimon;
    private UserDigimon $player2Digimon;
    private int $player1DigimonHp;
    private int $player2DigimonHp;
    private bool $isPlayer1Turn;

    public function setPlayers(UserDigimon $player1Digimon, UserDigimon $player2Digimon): void
    {
        $this->player1Digimon = $player1Digimon;
        $this->player2Digimon = $player2Digimon;

        $player1Bonuses = $player1Digimon->getLevelBasedIncreases();
        $player2Bonuses = $player2Digimon->getLevelBasedIncreases();

        $this->player1DigimonHp = $this->calculateInitialHp($player1Digimon->digimon->stage) + $player1Bonuses['hpBonus'];
        $this->player2DigimonHp = $this->calculateInitialHp($player2Digimon->digimon->stage) + $player2Bonuses['hpBonus'];

        $this->isPlayer1Turn = true;
    }

    public function start(): array
    {
        $events = [];
        while (!$this->isBattleOver()) {
            $event = $this->executeTurn();
            $events[] = $event;
            $this->switchTurns();
        }
        return $events;
    }

    protected function executeTurn(): array
    {
        $attacker = $this->getCurrentAttacker();
        $defender = $this->getCurrentDefender();

        // Calculate hit probability
        $hitProbability = (128 - ($defender->getPower() - $attacker->getPower())) / 256;

        // Check if the hit connects
        if (mt_rand() / mt_getrandmax() < $hitProbability) {
            // Calculate damage
            $damage = $this->calculateDamage($attacker, $defender);

            if ($defender === $this->player1Digimon) {
                $this->player1DigimonHp -= $damage;
            } else {
                $this->player2DigimonHp -= $damage;
            }

            // Add event to the list
            $event = [
                'type' => 'attack',
                'attacker' => $attacker->id,
                'defender' => $defender->id,
                'damage' => $damage,
            ];
        } else {
            // Add event to the list
            $event = [
                'type' => 'miss',
                'attacker' => $attacker->id,
                'defender' => $defender->id,
            ];
        }

        return $event;
    }

    protected function getCurrentAttacker(): UserDigimon
    {
        return $this->isPlayer1Turn ? $this->player1Digimon : $this->player2Digimon;
    }

    protected function getCurrentDefender(): UserDigimon
    {
        return $this->isPlayer1Turn ? $this->player2Digimon : $this->player1Digimon;
    }

    protected function switchTurns(): void
    {
        $this->isPlayer1Turn = !$this->isPlayer1Turn;
    }

    protected function calculateDamage(UserDigimon $attacker, UserDigimon $defender)
    {
        // You can adjust these values to fine-tune the damage calculation
        $minDamage = 1;
        $maxDamage = 4;

        // Calculate the base damage based on the attacker's level and training
        $levelFactor = $attacker->getLevel() / 10; // Assuming max level is 10
        $trainingFactor = $attacker->getTraining() / 50; // Assuming max number of trainings is 50

        // Combine level and training factors to calculate the final damage factor
        $damageFactor = $levelFactor * 0.5 + $trainingFactor * 0.5;

        // Calculate the damage range based on the damage factor
        $damageRange = $maxDamage - $minDamage;

        // Calculate the actual damage based on the damage factor and a random factor
        $damage = $minDamage + $damageRange * $damageFactor;
        $randomFactor = mt_rand(80, 120) / 100;

        return max(1, round($damage * $randomFactor));
    }

    private function isBattleOver(): bool
    {
        return $this->player1DigimonHp <= 0 || $this->player2DigimonHp <= 0;
    }

    public function getWinner(): ?UserDigimon
    {
        if ($this->isBattleOver()) {
            return $this->player1DigimonHp <= 0 ? $this->player2Digimon : $this->player1Digimon;
        }
        return null;
    }

    public function getLoser(): ?UserDigimon
    {
        if ($this->isBattleOver()) {
            return $this->player1DigimonHp <= 0 ? $this->player1Digimon : $this->player2Digimon;
        }
        return null;
    }

    private function calculateInitialHp(int $stage): int
    {
        return match ($stage) {
            1 => 1,
            2 => 5,
            3 => 10,
            4 => 12,
            5 => 14,
            6 => 16,
            default => 10,
        };
    }

    public function getLevelBasedIncreases(UserDigimon $userDigimon): array
    {
        $levelBonuses = [
            2 => ['hp' => 2, 'power' => 0],
            3 => ['hp' => 0, 'power' => 10],
            5 => ['hp' => 2, 'power' => 0],
            6 => ['hp' => 2, 'power' => 10],
            8 => ['hp' => 2, 'power' => 0],
            9 => ['hp' => 0, 'power' => 10],
            10 => ['hp' => 2, 'power' => 0],
        ];

        $hpBonus = 0;
        $powerBonus = 0;

        foreach ($levelBonuses as $levelThreshold => $bonus) {
            if ($userDigimon->getLevel() >= $levelThreshold) {
                $hpBonus += $bonus['hp'];
                $powerBonus += $bonus['power'];
            } else {
                break;
            }
        }

        return [
            'hpBonus' => $hpBonus,
            'powerBonus' => $powerBonus,
        ];
    }
}
