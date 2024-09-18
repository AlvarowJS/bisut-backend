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

        $data = Kardex::with('producto', 'almacen', 'compra', 'venta', 'operacion')
            ->when($producto, function ($query) use ($producto) {
                return $query->where('producto_id', $producto);
            })
            ->when($tienda, function ($query) use ($tienda) {
                return $query->where('almacen_id', $tienda);
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json($data);
    }
}
