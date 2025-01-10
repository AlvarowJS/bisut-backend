<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tienda = $request->almacen_id;
            $fecha = $request->fecha;
            $factura = $request->factura;

            // Crear la venta
            $venta = new Venta();
            $venta->importe = $request->importe;
            $venta->descuento = $request->descuento;
            $venta->subTotal = $request->subTotal;
            $venta->iva = $request->iva;
            $venta->flete = $request->flete;
            $venta->total = $request->total;
            $venta->puntos = $request->puntos;
            $venta->regalo = $request->regalo;
            $venta->tipo_factura = $request->tipo_factura;
            $venta->modo_pago = $request->modo_pago;
            $venta->tipo_pago = $request->tipo_pago;
            $venta->cfdi = $request->cfdi;
            $venta->tienda = $tienda;
            $venta->cfdi = $request->cfdi;
            $venta->cfdi = $request->cfdi;



        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
