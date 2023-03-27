<?php

namespace App\Http\Controllers;

use App\Models\Digimon\Digimon;
use App\Models\DigimonEgg;
use App\Models\UserDigimon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DigimonEggController extends Controller
{
    public function createEgg(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user === null)
        {
            return response()->json([
                'message' => 'Authentication unsuccessful.',
            ], 403);
        }

        $existingDigimon = UserDigimon::where('user_id', $user->id)->where('is_dead', false)->first();
        if ($existingDigimon !== null) {
            return response()->json([
                'message' => 'You can have only one Digimon.',
            ], 400);
        }

        $eggId = $request->input('egg_id');

        /** @var DigimonEgg $egg */
        $egg = DigimonEgg::find($eggId);
        if ($egg === null) {
            return response()->json([
                'message' => 'Egg not found.',
            ], 404);
        }

        /** @var Digimon $digimon */
        $digimon = Digimon::where('id', $egg->starter_digimon_id)->first();
        if ($digimon === null) {
            return response()->json([
                'message' => 'Digimon not found.',
            ], 404);
        }

        /** @var UserDigimon $newDigimon */
        $newDigimon = UserDigimon::create([
            'user_id' => $user->id,
            'digimon_id' => $egg->starter_digimon_id,
            'name' => $digimon->name
        ]);

        return response()->json([
            'message' => 'Digimon egg created successfully.',
            'digimon' => $newDigimon,
        ]);
    }
}
