<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\Battles\SingleBattle;
use App\Models\UserDigimon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BattleController extends Controller
{
    public function startBattle(Request $request, SingleBattle $battle): JsonResponse
    {
        $input = $request->all();

        $digimon1Id = $input['userDigimon1Id'];
        $digimon2Id = $input['userDigimon2Id'];

        $player1Digimon = UserDigimon::findOrFail($digimon1Id);
        $player2Digimon = UserDigimon::findOrFail($digimon2Id);

        $battle->setPlayers($player1Digimon, $player2Digimon);

        $events = $battle->start();
        $winner = $battle->getWinner();
        $loser = $battle->getLoser();

        $battleRecord = $this->createBattleRecord($player1Digimon, $player2Digimon, $winner, $events);

        $this->updateBattleStats($winner, $loser);

        return response()->json([
            'events' => $events,
            'battle_id' => $battleRecord->id,
        ]);
    }

    private function createBattleRecord(UserDigimon $player1Digimon, UserDigimon $player2Digimon, UserDigimon $winner, array $events): Battle
    {
        return Battle::create([
            'player1_digimon_id' => $player1Digimon->id,
            'player2_digimon_id' => $player2Digimon->id,
            'winner_digimon_id' => $winner?->id,
            'events' => $events,
        ]);
    }

    private function updateBattleStats(UserDigimon $winner, UserDigimon $loser)
    {
        $winner->addBattle();
        $winner->addBattleWon();
        $winner->addExp($this->calculateExpGained($loser, true));

        $loser->addBattle();
        $loser->addExp($this->calculateExpGained($winner, false));

        $winner->save();
        $loser->save();
    }

    private function calculateExpGained(UserDigimon $foughtDigimon, bool $isWon): int
    {
         // Different digimon stages add different amount of exp
        $stageExp = [
            1 => 10,
            2 => 20,
            3 => 50,
            4 => 100,
            5 => 200,
            6 => 500,
        ];

        $baseExp = $stageExp[$foughtDigimon->digimon->stage] ?? 0;
        $levelBonusExp = 2 * $foughtDigimon->getLevel();

        $expGained = $baseExp + $levelBonusExp;

        if (!$isWon) {
            $expGained /= 2;
        }

        return $expGained;
    }
}
