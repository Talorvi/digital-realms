<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\DigimonEggController;
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
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
