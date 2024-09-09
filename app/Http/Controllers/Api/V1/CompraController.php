<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Kardex;
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
        // // Validar la solicitud
        // $validatedData = $request->validate([
        //     'factura' => 'required|string',
        //     'fecha' => 'required|date',
        //     'total' => 'required|numeric',
        //     'cliente_id' => 'required|integer',
        //     'detalles' => 'required|array', // Detalles de la compra
        //     'detalles.*.item' => 'required|string',
        //     'detalles.*.descripcion' => 'nullable|string',
        //     'detalles.*.cajas' => 'required|integer',
        //     'detalles.*.precio_unitario' => 'required|numeric',
        //     'detalles.*.producto_id' => 'required|integer',
        // ]);

        // // Crear la compra
        // $compra = Compra::create([
        //     'factura' => $validatedData['factura'],
        //     'fecha' => $validatedData['fecha'],
        //     'total' => $validatedData['total'],
        //     'cliente_id' => $validatedData['cliente_id'],
        // ]);

        // // Crear los detalles de la compra
        // foreach ($validatedData['detalles'] as $detalle) {
        //     $detalle['compra_id'] = $compra->id; // Relacionar detalle con la compra
        //     DetalleCompra::create($detalle);
        // }

        // return response()->json(['message' => 'Compra creada exitosamente']);
        {
            DB::beginTransaction();
            try {
                // 1. Registrar la compra
                $compra = Compra::create([
                    'factura' => $request->factura,
                    'fecha' => $request->fecha,
                    'total' => $request->total,
                    'cliente_id' => $request->cliente_id
                ]);

                // 2. Registrar los detalles de la compra
                foreach ($request->detalles as $detalle) {
                    $detalleCompra = DetalleCompra::create([
                        'item' => $detalle['item'],
                        'descripcion' => $detalle['descripcion'],
                        'cajas' => $detalle['cajas'],
                        'precio_unitario' => $detalle['precio_unitario'],
                        'producto_id' => $detalle['producto_id'],
                        'compra_id' => $compra->id
                    ]);

                    // 3. Registrar en Kardex (Entrada)
                    $kardexEntrada = Kardex::create([
                        'fecha' => $request->fecha,
                        'documento' => $request->factura,
                        'cantidadEntrada' => $detalle['cajas'],
                        'vuEntrada' => $detalle['precio_unitario'],
                        'vtEntrada' => $detalle['cajas'] * $detalle['precio_unitario'],
                        'cantidadSalida' => 0, // No es una venta, entonces no hay salida
                        'vuSalida' => 0,
                        'vtSalida' => 0,
                        'cantidadSaldo' => $detalle['cajas'], // Ajustar el saldo según el stock
                        'vuSaldo' => $detalle['precio_unitario'],
                        'vtSaldo' => $detalle['cajas'] * $detalle['precio_unitario'],
                        'producto_id' => $detalle['producto_id'],
                        'operacion_id' => 1, // Suponiendo que 1 es para compra
                        'compra_id' => $compra->id,
                        'almacen_id' => $request->almacen_id
                    ]);
                }

                // 4. Registrar las ventas (si existen)
                if ($request->has('ventas')) {
                    foreach ($request->ventas as $venta) {
                        // Registrar venta en Kardex (Salida)
                        Kardex::create([
                            'fecha' => $venta['fecha'],
                            'documento' => $venta['documento'],
                            'cantidadEntrada' => 0, // No es una compra, entonces no hay entrada
                            'vuEntrada' => 0,
                            'vtEntrada' => 0,
                            'cantidadSalida' => $venta['cantidad'],
                            'vuSalida' => $venta['vuSalida'],
                            'vtSalida' => $venta['cantidad'] * $venta['vuSalida'],
                            'cantidadSaldo' => $venta['cantidad'], // Ajustar el saldo según el stock
                            'vuSaldo' => $venta['vuSalida'],
                            'vtSaldo' => $venta['cantidad'] * $venta['vuSalida'],
                            'producto_id' => $venta['producto_id'],
                            'operacion_id' => 2, // Suponiendo que 2 es para venta
                            'venta_id' => $venta['venta_id'],
                            'almacen_id' => $request->almacen_id
                        ]);
                    }
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Compra y ventas registradas correctamente.']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Error al registrar la compra/venta.', 'error' => $e->getMessage()]);
            }
        }
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
