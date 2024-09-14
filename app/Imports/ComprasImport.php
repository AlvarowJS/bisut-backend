<?php

namespace App\Imports;

use App\Models\Compra;
use App\Models\DetalleCompra;
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
        $compra = Compra::create([
            'factura' => $this->factura,
            'fecha' => $this->fecha,
            'proveedor_id' => $this->proveedor,
            'almacen_id' => $this->almacen,
        ]);
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
                'compra_id' => $compra->id,
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'total' => $cantidad * $precioUnitario,
            ]);
        }
    }
}
