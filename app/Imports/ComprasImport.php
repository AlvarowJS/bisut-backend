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
            // Obtener datos del Excel
            $item = $row[0];
            $descripcion = $row[1];
            $cajas = $row[2];
            $cantidadxCaja = $row[3];
            $cantidad = $row[4];
            $precioUnitario = $row[5];
            $familia = $row[6];
            $grupo = $row[7];
            $marca = $row[8];
            $unidad = $row[9];
            $precioLista = $row[10];
            $precio1 = $row[11];
            $precio2 = $row[12];
            $precio3 = $row[13];
            $precioEspecial = $row[14];
            $precioSuelto = $row[15];
            $piezasxpaquete = $row[16];
            $fiscal = $row[17];

            // Buscar si el producto ya existe por 'item'
            $producto = Producto::where('item', $item)->first();
            if ($producto) {
                // Actualizar los precios y datos del producto existente
                $producto->update([
                    'item' => $item,
                    'descripcion' => $descripcion,
                    'cajas' => $cajas,
                    'cantidad' => $cantidad,
                    'precioUnitario' => $precioUnitario,
                    'familia' => $familia,
                    'grupo' => $grupo,
                    'marca' => $marca,
                    'unidad' => $unidad,
                    'precioLista' => $precioLista,
                    'precio1' => $precio1,
                    'precio2' => $precio2,
                    'precio3' => $precio3,
                    'precioEspecial' => $precioEspecial,
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
                $producto->cantidad = $cantidad;
                $producto->precioUnitario = $precioUnitario;
                $producto->familia_id = $familia;
                $producto->grupo_id = $grupo;
                $producto->marca_id = $marca;
                $producto->unidad = $unidad;
                $producto->precioLista = $precioLista;
                $producto->precio1 = $precio1;
                $producto->precio2 = $precio2;
                $producto->precio3 = $precio3;
                $producto->precioEspecial = $precioEspecial;
                $producto->precioSuelto = $precioSuelto;
                $producto->piezasPaquete = $piezasxpaquete;
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
            $detalleCompra->precio_unitario = $precioUnitario;
            $detalleCompra->total = $cantidad * $precioUnitario;
            $detalleCompra->compra_id = $compra->id;
            $detalleCompra->producto_id = $producto->id;
            $detalleCompra->save();

            $totalCompra += $detalleCompra->total;

            // // Registro de kardex

            $this->registrarCompra($producto->id, $this->almacen, $compra->id, $this->fecha, $this->factura, $cantidad, $precioUnitario);
        }
        $compra->total = $totalCompra;
        $compra->save();
    }
}
