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
use App\Imports\ComprasImport;
use App\Traits\KardexTrait;
use Maatwebsite\Excel\Facades\Excel;

class CompraController extends Controller
{
    use KardexTrait;
    public function importarCompras(Request $request)
    {
        // Validar que el archivo y los otros campos estén presentes
        $request->validate([
            'factura' => 'required|string',
            'fecha' => 'required|date',
            'proveedor' => 'required|integer',
            'archivo' => 'required|file|mimes:xlsx,xls', // Solo permitir archivos Excel
        ]);

        // Obtener los datos adicionales
        $factura = $request->factura;
        $fecha = $request->fecha;
        $proveedor = $request->proveedor;
        $almacen = $request->almacen;

        // Subir y procesar el archivo Excel
        $file = $request->file('archivo');

        try {
            // Pasar los datos adicionales al importador
            Excel::import(new ComprasImport($factura, $fecha, $proveedor, $almacen), $file);

            // Responder con éxito en formato JSON
            return response()->json([
                'message' => 'Compras importadas correctamente'
            ], 200);
        } catch (\Exception $e) {
            // Manejar errores y devolver una respuesta con fallo
            return response()->json([
                'message' => 'Error al importar compras: ' . $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $data = Compra::with('proveedor', 'almacen')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $tienda = $request->almacen_id;
            $proveedor = $request->proveedor_id;
            $fecha = $request->fecha;
            $factura = $request->factura;

            // Crear la compra
            $compra = new Compra();
            $compra->factura = $factura;
            $compra->fecha = $fecha;
            $compra->total = 0;
            $compra->almacen_id = $tienda;
            $compra->proveedor_id = $proveedor;
            $compra->save();

            // Stock
            $stockController = new StockController();

            // Iterar sobre los detalles de la compra
            $totalCompra = 0;
            foreach ($request->detalles as $detalle) {
                $producto = Producto::where('item', $detalle['item'])->first();
                if ($producto) {
                    // Actualizar los precios y datos del producto existente
                    $producto->update([
                        'item' => $detalle['item'] ?? null,
                        'descripcion' => $detalle['descripcion'] ?? null,
                        'cajas' => $detalle['cajas'] ?? null,
                        'cantidadxCaja' => $detalle['cantidadxCaja'] ?? null,
                        'cantidad' => $detalle['cantidad'] ?? null,
                        'familia_id' => $detalle['familia_id'] ?? null,
                        'grupo_id' => $detalle['grupo_id'] ?? null,
                        'marca_id' => $detalle['marca_id'] ?? null,
                        'unidad' => $detalle['unidad'] ?? null,
                        'precio1' => $detalle['precio1'] ?? null,
                        'precio2' => $detalle['precio2'] ?? null,
                        'precio3' => $detalle['precio3'] ?? null,
                        'precio4' => $detalle['precio4'] ?? null,
                        'minimo' => $detalle['minimo'] ?? null,
                        'maximo' => $detalle['maximo'] ?? null,
                        'precioSuelto' => $detalle['precio_suelto'] ?? null,
                        'piezasPaquete' => $detalle['piezasPaquete'] ?? null,
                        'tono' => $detalle['tono'] ?? null,
                        'fiscal' => $detalle['fiscal'] ?? null,
                    ]);
                } else {
                    // Crear un nuevo producto
                    $producto = new Producto();
                    $producto->item = $detalle['item'] ?? null;
                    $producto->descripcion = $detalle['descripcion'] ?? null;
                    $producto->cajas = $detalle['cajas'] ?? null;
                    $producto->cantidadxCaja = $detalle['cantidadxCaja'] ?? null;
                    $producto->cantidad = $detalle['cantidad'] ?? null;
                    $producto->familia_id = $detalle['familia_id'] ?? null;
                    $producto->grupo_id = $detalle['grupo_id'] ?? null;
                    $producto->marca_id = $detalle['marca_id'] ?? null;
                    $producto->unidad = $detalle['unidad'] ?? null;
                    $producto->precio1 = $detalle['precio1'] ?? null;
                    $producto->precio2 = $detalle['precio2'] ?? null;
                    $producto->precio3 = $detalle['precio3'] ?? null;
                    $producto->precio4 = $detalle['precio4'] ?? null;
                    $producto->minimo = $detalle['minimo'] ?? null;
                    $producto->maximo = $detalle['maximo'] ?? null;
                    $producto->precioSuelto = $detalle['precio_suelto'] ?? null;
                    $producto->piezasPaquete = $detalle['piezasPaquete'] ?? null;
                    $producto->tono = $detalle['tono'] ?? null;
                    $producto->fiscal = $detalle['fiscal'] ?? null;
                    $producto->save();
                }
                // Crear el detalle de la compra
                $detalleCompra = new DetalleCompra();
                $detalleCompra->item = $producto->item;
                $detalleCompra->descripcion = $producto->descripcion;
                $detalleCompra->cantidad = $detalle['cantidad'];
                $detalleCompra->precio_unitario = $detalle['precio_suelto'];
                $detalleCompra->total = $detalle['cantidad'] * $detalle['precio_suelto'];
                $detalleCompra->producto_id = $producto->id;
                $detalleCompra->compra_id = $compra->id;
                $detalleCompra->save();

                $totalCompra += $detalle['cantidad'] * $detalle['precio_suelto'];

                // Crear o actualizar stock
                $stockController->store($producto->id, $tienda, $detalle['cantidad']);
                // Buscar la última operación en el Kardex

                $this->registrarCompra($producto->id, $tienda, $compra->id, $fecha, $factura, $detalle['cantidad'],$detalle['precio_suelto']);                
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
        $datos = Compra::with(['almacen', 'proveedor', 'detallesCompra'])->find($id);
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
