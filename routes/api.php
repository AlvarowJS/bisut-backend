<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController as Auth;
use App\Http\Controllers\Api\V1\ClienteController as Cliente;
use App\Http\Controllers\Api\V1\FamiliaController as Familia;
use App\Http\Controllers\Api\V1\GrupoController as Grupo;
use App\Http\Controllers\Api\V1\MarcaController as Marca;
use App\Http\Controllers\Api\V1\AlmacenController as Almacen;
use App\Http\Controllers\Api\V1\ProveedorController as Proveedor;
use App\Http\Controllers\Api\V1\ProductoController as Producto;
use App\Http\Controllers\Api\V1\CompraController as Compra;

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
    Route::apiResource('v1/familias', Familia::class);
    Route::apiResource('v1/grupos', Grupo::class);
    Route::apiResource('v1/marcas', Marca::class);
    Route::apiResource('v1/almacen', Almacen::class);
    Route::apiResource('v1/proveedor', Proveedor::class);
    Route::apiResource('v1/productos', Producto::class);
    Route::apiResource('v1/compras', Compra::class);
    Route::post('v1/producto-foto', [Producto::class, 'updateFoto']);

});