<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Kardex;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{

    public function index()
    {
        $data = Compra::with('detallesCompra')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tienda = $request->almacen_id;
            $cliente = $request->cliente_id;
            $proveedor = $request->proveedor_id;
            $fecha = $request->fecha;
            $factura = $request->factura;

            // Crear la compra
            $compra = new Compra();
            $compra->factura = $factura;
            $compra->fecha = $fecha;
            $compra->total = 0;
            $compra->cliente_id = $cliente;
            $compra->almacen_id = $tienda;
            $compra->proveedor_id = $proveedor;
            $compra->save();

            // Iterar sobre los detalles de la compra
            $totalCompra = 0;
            foreach ($request->detalles as $detalle) {
                $producto = Producto::where('item', $detalle['item'])->first();

                // Crear el detalle de la compra
                $detalleCompra = new DetalleCompra();
                $detalleCompra->item = $producto->item;
                $detalleCompra->descripcion = $producto->descripcion;
                $detalleCompra->cantidad = $detalle['cantidad'];
                $detalleCompra->precio_unitario = $detalle['precio_unitario'];
                $detalleCompra->producto_id = $producto->id;
                $detalleCompra->compra_id = $compra->id;
                $detalleCompra->save();

                $totalCompra += $detalle['cantidad'] * $detalleCompra['precio_unitario'];
                // Buscar la última operación en el Kardex
                $operacion = Kardex::where('producto_id', $producto->id)
                    ->where('almacen_id', $tienda)
                    ->latest('id')
                    ->first();


                // Definir tipo de operación: 2 = compra anterior, 1 = stock inicial
                $operacionTipo = $operacion ? 2 : 1;

                // Calcular el saldo dependiendo de la operación
                if ($operacionTipo == 1) {
                    $cantidadSaldo = $detalle['cantidad'];
                    $vuSaldo = $detalle['precio_unitario'];
                    $vtSaldo = $detalle['cantidad'] * $detalle['precio_unitario'];
                } else {
                    $cantidadSaldo = $operacion->cantidadSaldo + $detalle['cantidad'];
                    $vtSaldo = $operacion->vtSaldo + ($detalle['cantidad'] * $detalle['precio_unitario']);
                    $vuSaldo = $vtSaldo / $cantidadSaldo;
                }

                // Crear el registro en el Kardex
                $kardex = new Kardex();
                $kardex->fecha = $fecha;
                $kardex->documento = $factura;
                $kardex->cantidadEntrada = $detalle['cantidad'];
                $kardex->vuEntrada = $detalle['precio_unitario'];
                $kardex->vtEntrada = $detalle['cantidad'] * $detalle['precio_unitario'];
                $kardex->cantidadSalida = 0;
                $kardex->vuSalida = 0;
                $kardex->vtSalida = 0;
                $kardex->cantidadSaldo = $cantidadSaldo;
                $kardex->vuSaldo = $vuSaldo;
                $kardex->vtSaldo = $vtSaldo;
                $kardex->producto_id = $producto->id;
                $kardex->operacion_id = $operacionTipo;
                $kardex->compra_id = $compra->id;
                $kardex->almacen_id = $tienda;
                $kardex->save();
            }
            $compra->total = $totalCompra;
            $compra->save();
            // Si todo va bien, confirmar la transacción
            DB::commit();
        } catch (\Exception $e) {
            // Si ocurre algún error, revertir la transacción
            DB::rollBack();
            // Manejar el error, por ejemplo, lanzar una excepción o devolver una respuesta de error
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Compra registrada con éxito'], 200);
    }
    public function show(string $id)
    {
        $datos = Compra::find($id);

        if (!$datos) {
            return response()->json(['message' => 'Registro no encontrado'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($datos);
    }

    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        $compra = Compra::findOrFail($id);

        // Eliminar los detalles de la compra asociados
        $compra->detalles()->delete();

        // Eliminar la compra
        $compra->delete();

        return response()->json(['message' => 'Compra y sus detalles eliminados exitosamente']);
    }
}
