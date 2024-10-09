<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{

    public function index() {}


    public function store($producto_id, $almacen_id, $cantidad)
    {
        $stock = Stock::where('producto_id', $producto_id)
            ->where('almacen_id', $almacen_id)
            ->first();

        if ($stock) {
            $stock = new Stock();
            $stock->producto_id = $producto_id;
            $stock->producto_id = $producto_id;
            $stock->producto_id = $producto_id;
        } else {
            $stock->cantidad += $cantidad;
        }
        $stock->save();

        return response()->json([
            'message' => 'Stock actualizado correctamente'
        ], 200);
    }


    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
