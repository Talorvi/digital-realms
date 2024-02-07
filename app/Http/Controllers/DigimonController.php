<?php

namespace App\Http\Controllers;

use App\Services\DigimonEvolutionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DigimonController extends Controller
{
    protected DigimonEvolutionService $digimonEvolutionService;

    public function __construct(DigimonEvolutionService $digimonEvolutionService)
    {
        $this->digimonEvolutionService = $digimonEvolutionService;
    }

    public function evolutionTree(Request $request): JsonResponse
    {
        $rootName = $request->input('root', 'Botamon');
        $tree = $this->digimonEvolutionService->buildEvolutionTree($rootName);

        if (empty($tree)) {
            return response()->json(['error' => 'Specified root Digimon not found.'], 404);
        }

        return response()->json($tree);
    }
}
