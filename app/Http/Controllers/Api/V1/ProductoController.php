<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Traits\GuardaImagenTrait;
use App\Traits\StockTrait;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use GuardaImagenTrait;
    use StockTrait;
    public function mostrarTodo()
    {
        $productos = Producto::with('familia', 'grupo', 'marca')->get();
        return response()->json($productos);
    }
    public function index()
    {
        $data = [];
        $tiendaId = request()->input('tiendaId');
        $productos = Producto::with('familia', 'grupo', 'marca')->get();
    
        if (!$tiendaId) {
            $data = $productos->map(function ($producto) {
                $producto->setAttribute('stock', "Seleccione una tienda");
                return $producto;
            });
        } else {
            $data = $productos->map(function ($producto) use ($tiendaId) {
                $producto->setAttribute('stock', $this->verStock($tiendaId, $producto->id));
                return $producto;
            });
        }
    
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
        $producto->minimo = $request->minimo;
        $producto->maximo = $request->maximo;
        $producto->precioSuelto = $request->precioSuelto;
        $producto->piezasPaquete = $request->piezasPaquete;
        $producto->unidad = $request->unidad;
        $producto->tono = $request->tono;
        $producto->foto = $foto;
        $producto->familia_id = $request->familia_id;
        $producto->grupo_id = $request->grupo_id;
        $producto->marca_id = $request->marca_id;
        $producto->save();

        return response()->json($producto);
    }


    public function show($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json(["message" => "not found"], 404);
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
        $foto = $this->actualizarImagen("productos", $producto->foto, $producto->item, $item, "foto", $request);
        $producto->item = $item;
        $producto->descripcion = $request->descripcion;
        $producto->precio1 = $request->precio1;
        $producto->precio2 = $request->precio2;
        $producto->precio3 = $request->precio3;
        $producto->precio4 = $request->precio4;
        $producto->minimo = $request->minimo;
        $producto->maximo = $request->maximo;
        $producto->precioSuelto = $request->precioSuelto;
        $producto->piezasPaquete = $request->piezasPaquete;
        $producto->unidad = $request->unidad;
        $producto->tono = $request->tono;
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
        if (!$producto) {
            return response()->json(["message" => "not found"], 404);
        }

        if ($producto->foto) {
            $this->eliminarImagen("productos", $producto->item, $producto->foto);
        }
        $producto->delete();
        return response()->json(["message" => "Producto eliminado"], 200);
    }
}
