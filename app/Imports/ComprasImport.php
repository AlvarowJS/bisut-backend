<?php

namespace App\Imports;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Kardex;
use App\Models\Producto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ComprasImport implements ToCollection
{
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
                Producto::create([
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
            }
            DetalleCompra::create([
                'item' => $item,
                'descripcion' => $descripcion,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'total' => $cantidad * $precioUnitario,
                'compra_id' => $compra->id,
                'producto_id' => $producto->id,
            ]);

            // // Registro de kardex
            // // Buscar la última operación en el Kardex
            // $operacion = Kardex::where('producto_id', $producto->id)
            //     ->where('almacen_id', $this->almacen)
            //     ->latest('id')
            //     ->first();


            // // Definir tipo de operación: 2 = compra anterior, 1 = stock inicial
            // $operacionTipo = $operacion ? 2 : 1;

            // // Calcular el saldo dependiendo de la operación
            // if ($operacionTipo == 1) {
            //     $cantidadSaldo = $cantidad;
            //     $vuSaldo = $precioUnitario;
            //     $vtSaldo = $cantidad * $precioUnitario;
            // } else {
            //     $cantidadSaldo = $operacion->cantidadSaldo + $detalle['cantidad'];
            //     $vtSaldo = $operacion->vtSaldo + ($detalle['cantidad'] * $detalle['precio_unitario']);
            //     $vuSaldo = $vtSaldo / $cantidadSaldo;
            // }

            // $kardex = new Kardex();
            // $kardex->fecha = $this->fecha;
            // $kardex->documento = $this->factura;
            // $kardex->cantidadEntrada = $cantidad;
            // $kardex->vuEntrada = $precioUnitario;
            // $kardex->vtEntrada = $detalle['cantidad'] * $detalle['precio_unitario'];
            // $kardex->cantidadSalida = 0;
            // $kardex->vuSalida = 0;
            // $kardex->vtSalida = 0;
            // $kardex->cantidadSaldo = $cantidadSaldo;
            // $kardex->vuSaldo = $vuSaldo;
            // $kardex->vtSaldo = $vtSaldo;
            // $kardex->producto_id = $producto->id;
            // $kardex->operacion_id = $operacionTipo;
            // $kardex->compra_id = $compra->id;
            // $kardex->almacen_id = $tienda;
        }
        $compra->total = $totalCompra;
        $compra->save();
    }
}
