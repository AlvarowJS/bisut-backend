<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Kardex;
use App\Models\Producto;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    public function index()
    {
        $producto =  request()->input('producto');
        $fechaInicio =  request()->input('fechaInicio');
        $fechaFin =  request()->input('fechaFin');
        $tienda = request()->input('tienda');

        // $producto = Producto::where('item', $producto)->first();

        $data = Kardex::with('producto','almacen','compra','venta','operacion')
                ->where('producto_id', $producto)
                ->where('almacen_id', $tienda)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->orderBy('fecha', 'asc')
                ->get();

        return response()->json($data);
    }
}
