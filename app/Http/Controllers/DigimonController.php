<?php

namespace App\Http\Controllers;

use App\Services\DigimonEvolutionService;
use Illuminate\Http\JsonResponse;

class DigimonController extends Controller
{
    protected DigimonEvolutionService $digimonEvolutionService;

    public function __construct(DigimonEvolutionService $digimonEvolutionService)
    {
        $this->digimonEvolutionService = $digimonEvolutionService;
    }

    public function evolutionTree(): JsonResponse
    {
        $tree = $this->digimonEvolutionService->buildEvolutionTree();
        return response()->json($tree);
    }
}
