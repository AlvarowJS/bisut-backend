<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
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
        // Validar la solicitud
        $validatedData = $request->validate([
            'factura' => 'required|string',
            'fecha' => 'required|date',
            'total' => 'required|numeric',
            'cliente_id' => 'required|integer',
            'detalles' => 'required|array', // Detalles de la compra
            'detalles.*.item' => 'required|string',
            'detalles.*.descripcion' => 'nullable|string',
            'detalles.*.cajas' => 'required|integer',
            'detalles.*.precio_unitario' => 'required|numeric',
            'detalles.*.producto_id' => 'required|integer',
        ]);

        // Crear la compra
        $compra = Compra::create([
            'factura' => $validatedData['factura'],
            'fecha' => $validatedData['fecha'],
            'total' => $validatedData['total'],
            'cliente_id' => $validatedData['cliente_id'],
        ]);

        // Crear los detalles de la compra
        foreach ($validatedData['detalles'] as $detalle) {
            $detalle['compra_id'] = $compra->id; // Relacionar detalle con la compra
            DetalleCompra::create($detalle);
        }

        return response()->json(['message' => 'Compra creada exitosamente']);
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
