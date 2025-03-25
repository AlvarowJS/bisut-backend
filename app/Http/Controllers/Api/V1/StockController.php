<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Traits\KardexTrait;
use Illuminate\Http\Request;

class StockController extends Controller
{
    use KardexTrait;

    public function mostrarStockProducto($producto_id, $almacen_id)
    {
        $stock = Stock::where('producto_id', $producto_id)
            ->where('almacen_id', $almacen_id)
            ->first();

        return $stock;
    }
    public function store($producto_id, $almacen_id, $cantidad)
    {
        $stock = Stock::where('producto_id', $producto_id)
            ->where('almacen_id', $almacen_id)
            ->first();

        if (!$stock) {
            $stock = new Stock();
            $stock->producto_id = $producto_id;
            $stock->cantidad = $cantidad;
            $stock->almacen_id = $almacen_id;
        } else {
            $stock->cantidad += $cantidad;
        }
        $stock->save();

        return response()->json([
            'message' => 'Stock actualizado correctamente'
        ], 200);
    }

    public function storeVenta($producto_id, $almacen_id, $cantidad)
    {
        $stock = Stock::where('producto_id', $producto_id)
            ->where('almacen_id', $almacen_id)
            ->first();

        if (!$stock) {
            return response()->json([
                'message' => 'Stock no encontrado'
            ], 404);
        } else {
            $stock->cantidad -= $cantidad;
            $stock->save();
            return response()->json([
                'message' => 'Stock actualizado correctamente',
                'data' => $cantidad
            ], 200);
        }
    }

    public function docenasAPiezas(Request $request)
    {
        $producto_id_emisor = $request->producto_id_emisor;
        $producto_id_receptor = $request->producto_id_receptor;
        $cajas = $request->cajas;
        $almacen_id = $request->almacen_id;
        $fecha = $request->fecha;

        $stockEmisor = Stock::where('producto_id', $producto_id_emisor)
            ->with('producto')
            ->first();
        
        $piezasxPaquete = $stockEmisor->producto->piezasPaquete;
        $cantidadPiezas = $cajas * $piezasxPaquete;

        $this->storeVenta($producto_id_emisor, $almacen_id, $cantidadPiezas);
        $this->store($producto_id_receptor, $almacen_id, $cantidadPiezas);

        $this->conversionDocenasAPiezasEmisor($producto_id_emisor,  $cajas, $almacen_id, $fecha);
        $this->conversionDocenasAPiezasReceptor($producto_id_receptor,  $cantidadPiezas, $almacen_id, $fecha);


        return response()->json([
            'message' => 'Operacion realizada correctamente'
        ], 200);
    }
}
