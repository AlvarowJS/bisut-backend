<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $data = Proveedor::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userCurrent = auth()->id();
        $data = Proveedor::create();
        $data ->user_id = $userCurrent;
        $data -> nombre = $request->nombre;
        $data -> telefono = $request->telefono;
        $data -> mail = $request->mail;
        $data -> status = true;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Proveedor::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Proveedor::find($id);
        $data -> user_id = $request->user_id;
        $data -> nombre = $request->nombre;
        $data ->telefono = $request->telefono;
        $data -> mail = $request->mail;
        $data -> status = true;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
