<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Training;
use App\Models\UserDigimon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserDigimonController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userDigimons = UserDigimon::where('user_id', $user->id)->with('digimon')->get();

        // Add the stage name mapping
        $stageNames = [
            1 => 'Baby',
            2 => 'In-Training',
            3 => 'Rookie',
            4 => 'Champion',
            5 => 'Ultimate',
            6 => 'Mega',
        ];

        $userDigimons->map(function ($userDigimon) use ($stageNames) {
            // Replace the stage integer value with the corresponding stage name
            $userDigimon->digimon->stage = $stageNames[$userDigimon->digimon->stage];
            $userDigimon->image = url('/icons/' . $userDigimon->digimon->stage . '/' . $userDigimon->digimon->name . '.gif');
            return $userDigimon;
        });

        return response()->json($userDigimons);
    }

    public function train(Request $request, UserDigimon $userDigimon): JsonResponse
    {
        $training = Training::where('id', $request->input('training_id'))->first();
        $userDigimon->train($training);
        $userDigimon->save();

        return response()->json(['message' => 'Digimon trained successfully.']);
    }

    public function feed(Request $request, UserDigimon $userDigimon): JsonResponse
    {
        $food = Food::where('id', $request->input('food_id'))->first();
        $userDigimon->feed($food);
        $userDigimon->save();

        return response()->json(['message' => 'Digimon fed successfully.']);
    }

    public function clean(UserDigimon $userDigimon): JsonResponse
    {
        $userDigimon->clean();
        $userDigimon->save();

        return response()->json(['message' => 'Digimon cleaned successfully.']);
    }

    public function sleep(UserDigimon $userDigimon): JsonResponse
    {
        $userDigimon->turnOffLights();
        $userDigimon->save();

        return response()->json(['message' => 'Digimon put to sleep successfully.']);
    }

    public function wakeUp(UserDigimon $userDigimon): JsonResponse
    {
        $userDigimon->wakeup();
        $userDigimon->save();

        return response()->json(['message' => 'Digimon woke up successfully.']);
    }
}
