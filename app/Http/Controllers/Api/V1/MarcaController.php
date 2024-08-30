<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function index()
    {
        $data = Marca::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = new Marca();
        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;
        $data->save();
        return response()->json($data);
    }
    public function show(string $id)
    {
        $data = Marca::find($id);
        if (!$data) {
            return response()->json(["message" => "not found"], 404);
        }
        return response()->json($data);
    }

    public function update(Request $request, string $id)
    {
        $data = Marca::find($id);
        if (!$data) {
            return response()->json(["message" => "not found"], 404);
        }
        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;
        $data->save();
        return response()->json($data);
    }
    public function destroy(string $id)
    {
        $data = Marca::find($id);
        if (!$data) {
            return response()->json(["message" => "not found"], 404);
        }
        $data->delete();
        return response()->json($data);
    }
}
