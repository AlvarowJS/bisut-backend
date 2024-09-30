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
            'familia',
            'grupo',
            'marca'
        )->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {

        $item = $request->item;
        $foto = $this->guardarImagen("productos", $item, "foto", $request);
        $producto = new Producto;
        $producto->item = $request->item;
        $producto->descripcion = $request->descripcion;
        $producto->precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precio4 = $request->precio4;
        // $producto->precioUnitario = $request->precioUnitario;
        // $producto->precioLista = $request->precioLista;
        $producto->precioSuelto = $request->precioSuelto;
        $producto->precioEspecial = $request->precioEspecial;
        $producto->piezasPaquete = $request->piezasPaquete;
        $producto->unidad = $request->unidad;
        $producto->foto = $foto;
        $producto->familia_id = $request->familia_id;
        $producto->grupo_id = $request->grupo_id;
        $producto->marca_id = $request->marca_id;
        $producto->save();

        return response()->json($producto);
    }


    public function show(string $id)
    {
        $producto = Producto::find($id);
        if(!$producto){
            return response()->json(["message"=> "not found"],404);
        }
        return response()->json($producto);
    }


    public function update(Request $request, string $id)
    {
        //
    }

    public function updateFoto(Request $request)
    {
        $id = $request->id;
        $producto = Producto::find($id);
        $item = $request->item;
        if (!$producto) {
            return response()->json(['error' => 'prodcuto no encontrado'], 404);
        }
        $item = $request->item;
        $foto = $this->actualizarImagen("productos", $producto->foto,$producto->item, $item, "foto", $request);
        $producto->item = $item;
        $producto->descripcion = $request->descripcion;
        $producto->precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precioUnitario = $request->precioUnitario;
        // $producto->precioLista = $request->precioLista;
        $producto->precioSuelto = $request->precioSuelto;
        // $producto->precioEspecial = $request->precioEspecial;
        $producto->piezasPaquete = $request->piezasPaquete;
        $producto->unidad = $request->unidad;
        $producto->foto = $foto;
        $producto->familia_id = $request->familia_id;
        $producto->grupo_id = $request->grupo_id;
        $producto->marca_id = $request->marca_id;
        $producto->save();
        return response()->json($producto, 200);
    }

    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        if(!$producto){
            return response()->json(["message"=> "not found"],404);
        }
        
        if($producto->foto){
            $this->eliminarImagen("productos",$producto->item, $producto->foto);
        }
        $producto->delete();
        return response()->json(["message"=> "Producto eliminado"],200);
    }
}
