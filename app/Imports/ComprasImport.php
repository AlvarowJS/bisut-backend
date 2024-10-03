<?php

namespace App\Imports;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Kardex;
use App\Models\Producto;
use App\Traits\KardexTrait;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ComprasImport implements ToCollection
{
    use KardexTrait;
    protected $factura;
    protected $fecha;
    protected $proveedor;
    protected $almacen;
    public function __construct($factura, $fecha, $proveedor, $almacen)
    {
        $this->factura = $factura;
        $this->fecha = $fecha;
        $this->proveedor = $proveedor;
        $this->almacen = $almacen;
    }

    /**
     * Handle the import collection.
     * 
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $rows = $rows->slice(1);
        // $compra = Compra::create([
        //     'factura' => $this->factura,
        //     'fecha' => $this->fecha,
        //     'proveedor_id' => $this->proveedor,
        //     'almacen_id' => $this->almacen,
        // ]);

        $compra = new Compra();
        $compra->factura = $this->factura;
        $compra->fecha = $this->fecha;
        $compra->proveedor_id = $this->proveedor;
        $compra->almacen_id = $this->almacen;
        $compra->total = 0;
        $compra->save();

        $totalCompra = 0;
        foreach ($rows as $row) {
            $item = $row[0];
            // if (is_null($item) || trim($item) === '') {
            //     break;
            // }
            // Obtener datos del Excel
            $descripcion = isset($row[1]) ? $row[1] : null;
            $cajas = isset($row[2]) ? $row[2] : null;
            $cantidadxCaja = isset($row[3]) ? $row[3] : null;
            $cantidad = isset($row[4]) ? $row[4] : null;
            $familia = isset($row[5]) ? $row[5] : null;
            $grupo = isset($row[6]) ? $row[6] : null;
            $marca = isset($row[7]) ? $row[7] : null;
            $unidad = isset($row[8]) ? $row[8] : null;
            $precio1 = isset($row[9]) ? $row[9] : null;
            $precio2 = isset($row[10]) ? $row[10] : null;
            $precio3 = isset($row[11]) ? $row[11] : null;
            $precioSuelto = isset($row[12]) ? $row[12] : null;
            $piezasxpaquete = isset($row[13]) ? $row[13] : null;
            $tono = isset($row[14]) ? $row[14] : null;
            $fiscal = isset($row[15]) ? $row[15] : null;

            // Buscar si el producto ya existe por 'item'
            $producto = Producto::where('item', $item)->first();
            if ($producto) {
                // Actualizar los precios y datos del producto existente
                $producto->update([
                    'item' => $item,
                    'descripcion' => $descripcion,
                    'cajas' => $cajas,
                    'cantidadxCaja' => $cantidadxCaja,
                    'cantidad' => $cantidad,
                    'familia' => $familia,
                    'grupo' => $grupo,
                    'marca' => $marca,
                    'unidad' => $unidad,
                    'precio1' => $precio1,
                    'precio2' => $precio2,
                    'precio3' => $precio3,
                    'precioSuelto' => $precioSuelto,
                    'piezasPaquete' => $piezasxpaquete,
                    'fiscal' => $fiscal,
                ]);
            } else {
                // Crear un nuevo producto si no existe
                // Producto::create([
                //     'item' => $item,
                //     'descripcion' => $descripcion,
                //     'cajas' => $cajas,
                //     'cantidad' => $cantidad,
                //     'precioUnitario' => $precioUnitario,
                //     'familia_id' => $familia,
                //     'grupo_id' => $grupo,
                //     'marca_id' => $marca,
                //     'unidad' => $unidad,
                //     'precioLista' => $precioLista,
                //     'precio1' => $precio1,
                //     'precio2' => $precio2,
                //     'precio3' => $precio3,
                //     'precioEspecial' => $precioEspecial,
                //     'precioSuelto' => $precioSuelto,
                //     'piezasPaquete' => $piezasxpaquete,
                //     'fiscal' => $fiscal,
                // ]);

                $producto = new Producto();
                $producto->item = $item;
                $producto->descripcion = $descripcion;
                $producto->cajas = $cajas;
                $producto->cantidadxCaja = $cantidadxCaja;                
                $producto->cantidad = $cantidad;
                $producto->familia_id = $familia;
                $producto->grupo_id = $grupo;
                $producto->marca_id = $marca;
                $producto->unidad = $unidad;
                $producto->precio1 = $precio1;
                $producto->precio2 = $precio2;
                $producto->precio3 = $precio3;
                $producto->precioSuelto = $precioSuelto;
                $producto->piezasPaquete = $piezasxpaquete;
                $producto->tono = $tono;
                $producto->fiscal = $fiscal;
                $producto->save();
            }

            // DetalleCompra::create([
            //     'item' => $item,
            //     'descripcion' => $descripcion,
            //     'cantidad' => $cantidad,
            //     'precio_unitario' => $precioUnitario,
            //     'total' => $cantidad * $precioUnitario,
            //     'compra_id' => $compra->id,
            //     'producto_id' => $producto->id,
            // ]);

            $detalleCompra = new DetalleCompra();
            $detalleCompra->item = $item;
            $detalleCompra->descripcion = $descripcion;
            $detalleCompra->cantidad = $cantidad;
            $detalleCompra->precio_unitario = $precio1;
            $detalleCompra->total = $cantidad * $precio1;
            $detalleCompra->compra_id = $compra->id;
            $detalleCompra->producto_id = $producto->id;
            $detalleCompra->save();

            $totalCompra += $detalleCompra->total;

            // // Registro de kardex

            $this->registrarCompra($producto->id, $this->almacen, $compra->id, $this->fecha, $this->factura, $cantidad, $precio1);
        }
        $compra->total = $totalCompra;
        $compra->save();
    }
}
