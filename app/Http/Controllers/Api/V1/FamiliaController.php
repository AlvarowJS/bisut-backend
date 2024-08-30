<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use App\Models\Familia;
use Illuminate\Http\Request;

class FamiliaController extends Controller
{

    public function index()
    {
        $data = Familia::all();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = new Familia();
        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;
        $data->save();
        return response()->json($data);
    }
    public function show(string $id)
    {
        $data = Familia::find($id);
        if (!$data) {
            return response()->json(["message" => "not found"], 404);
        }
        return response()->json($data);
    }

    public function update(Request $request, string $id)
    {
        $data = Familia::find($id);
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
        $data = Familia::find($id);
        if (!$data) {
            return response()->json(["message" => "not found"], 404);
        }
        $data->delete();
        return response()->json($data);
    }
}
