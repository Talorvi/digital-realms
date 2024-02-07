<?php

use App\Http\Controllers\API\BattleController;
use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\API\DigimonEggController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserDigimonController;
use App\Http\Controllers\DigimonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->controller(DigimonEggController::class)->group(function () {
    Route::post('egg/choose', 'createEgg');
    Route::get('egg/get', 'getEggs');
});

Route::middleware('auth:sanctum')->controller(BattleController::class)->group(function () {
    Route::post('battle/start', 'startBattle');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
    Route::get('/device-tokens', [DeviceTokenController::class, 'index']);
    Route::delete('/device-tokens/{deviceToken}', [DeviceTokenController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user-digimons', [UserDigimonController::class, 'index']);
    Route::post('/user-digimons/{userDigimon}/feed', [UserDigimonController::class, 'feed']);
    Route::post('/user-digimons/{userDigimon}/train', [UserDigimonController::class, 'train']);
    Route::post('/user-digimons/{userDigimon}/clean', [UserDigimonController::class, 'clean']);
    Route::post('/user-digimons/{userDigimon}/sleep', [UserDigimonController::class, 'sleep']);
    Route::post('/user-digimons/{userDigimon}/wakeup', [UserDigimonController::class, 'wakeUp']);
});

Route::get('/digimon/evolution-tree', [DigimonController::class, 'evolutionTree']);
