<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Venta;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{

    public function ultimoId($tipoFactura)
    {
        $data = Venta::where('tipo_factura', $tipoFactura)->get();
        return  count($data)+1;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Venta::with('detallesVenta', 'almacen', 'user', 'cliente')->get(); 
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Tipo factura
        // remision = 1, factura = 2, cotizacion = 3
        $userCurrent = auth()->id();
        DB::beginTransaction();
        try {
            $tienda = $request->almacen_id;
            $fecha = $request->fecha;
            $tipoFactura = $request->tipo_factura;
            // Crear la venta
            $venta = new Venta();
            $venta->medio_pago = $request->medio_pago;
            $venta->medio_pago_monto = $request->medio_pago_monto;
            $venta->importe = $request->importe;
            $venta->descuento = $request->descuento;
            $venta->subTotal = $request->subtotal;
            $venta->iva = $request->iva;
            $venta->flete = $request->flete;
            $venta->total = $request->total;
            $venta->puntos = $request->puntos;
            $venta->regalo = $request->regalo;
            $venta->tipo_factura = $tipoFactura;
            $venta->modo_pago = $request->modo_pago;
            $venta->tipo_pago = $request->tipo_pago;
            $venta->cfdi = $request->cfdi;
            $venta->almacen_id = $tienda;
            $venta->fecha = $fecha;
            $venta->hora = $request->hora;
            $venta->user_id = $userCurrent;
            $venta->cliente_id = $request->cliente_id;
            $venta->user_id = $userCurrent;
            // Registrar identificador 
            $valIdentificador = $this->ultimoId($tipoFactura);
            $venta->identificador = $valIdentificador;

            $venta->save();

            // Stock
            $stockController = new StockController();

            //iterar
            foreach ($request->detalles as $detalle) {
                $producto = Producto::where('item', $detalle['item'])->first();
                if ($producto) {
                    // Creara el producto en la venta
                    $detallesVenta = new DetalleVenta();
                    $detallesVenta->item = $detalle['item'];
                    $detallesVenta->cantidad_venta = $detalle['cantidad_venta'];
                    $detallesVenta->descripcion = $detalle['descripcion'];
                    $detallesVenta->descuento = $detalle['descuento'];
                    $detallesVenta->importe = $detalle['importe'];
                    $detallesVenta->precio_suelto = $detalle['precio_suelto'];
                    $detallesVenta->precio_venta = $detalle['precio_venta'];
                    $detallesVenta->stock = $detalle['stock'];
                    $detallesVenta->total_item = $detalle['total_item'];
                    $detallesVenta->venta_id = $venta->id;
                    $detallesVenta->producto_id = $producto->id;
                    $detallesVenta->save();

                    $stockController->storeVenta($producto->id, $tienda, $detalle['cantidad_venta']);
                } else {
                    throw new \Exception('Producto no encontrado: ' . $detalle['item']);
                }
            }
            DB::commit();
            return response()->json([
                'message' => "Venta registrada",
                'data' => $venta
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar la venta: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos = Venta::with(['almacen', 'cliente', 'detallesVenta','user'])->find($id);
        if (!$datos) {
            return response()->json(['message' => 'Registro no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($datos);
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
