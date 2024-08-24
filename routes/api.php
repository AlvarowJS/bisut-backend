<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as Auth;
use App\Http\Controllers\Api\V1\ClienteController as Cliente;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [Auth::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/token-auth', [Auth::class, 'authToken']);
    Route::apiResource('/v1/clientes', Cliente::class);
    Route::apiResource('/users', Auth::class);
});