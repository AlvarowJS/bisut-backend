<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Traits\GuardaImagenTrait;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use GuardaImagenTrait;
    public function index()
    {
        $data = Producto::with(
            'familia', 'grupo', 'marca'
        )->first();

        return response()->json($data);

    }

    public function store(Request $request)
    {   
        
        $item = $request->item;
        $foto = $this->guardarImagen("productos",$item, "foto",$request);

        
        $producto = new Producto;
        $producto->item = $request->item;
        $producto->descripcion = $request->descripcion;
        $producto->precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precioUnitario = $request->precioUnitario;
        $producto->precioLista = $request->precioLista;
        $producto->precioSuelto = $request->precioSuelto;
        $producto->precioEspecial = $request->precioEspecial;
        $producto->piezasPaquete = $request->piezasPaquete;
        $producto->foto = $foto;
        $producto->save();
        
        return response()->json($producto);    
    }

    
    public function show(string $id)
    {
        //
    }

    
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
