<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        $data = Almacen::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = new Almacen();
        $data->nombre = $request->nombre;
        $data->direccion = $request->direccion;
        $data->telefono = $request->telefono;
        $data->tipo = $request->tipo;
        $data->save();
        return response()->json($data);
    }

    public function show(string $id)
    {
        $data = Almacen::find($id);
        return response()->json($data);
    }

    public function update(Request $request, string $id)
    {
        $data = Almacen::find($id);
        $data ->nombre = $request->nombre;
        $data->direccion = $request->direccion;
        $data->telefono = $request->telefono;
        $data->tipo = $request->tipo;
        $data->save();
        return response()->json($data);
    }

    public function destroy(string $id)
    {
        $data = Almacen::find($id);
        if (!$data) {
            return response()->json(["message" => "not found"], 404);
        }
        $data->delete();
        return response()->json($data);
    }
}
