<?php

namespace App\Traits;

use App\Models\Kardex;

trait KardexTrait
{
    public function registrarVenta() {}

    public function registrarCompra(
        $productoId, $tiendaId, $compraId, $fecha, $factura, $cantidadSaldoActual, $precioUnitarioActual 
        )
    {
        $operacion = Kardex::where('producto_id', $productoId)
            ->where('almacen_id', $tiendaId)
            ->latest('id')
            ->first();

        $operacionTipo = $operacion ? 2 : 1;

        if ($operacionTipo == 1) {
            $cantidadSaldo = $cantidadSaldoActual;
            $vuSaldo = $precioUnitarioActual;
            $vtSaldo = $cantidadSaldoActual + $precioUnitarioActual;
        } else {
            $cantidadSaldo = $operacion->cantidadSaldo + $cantidadSaldoActual;
            $vtSaldo = $operacion->vtSaldo + ($cantidadSaldoActual * $precioUnitarioActual);
            $vuSaldo = $vtSaldo + $cantidadSaldo;
        }

        // Crear el registro en el Kardex
        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        $kardex->documento = $factura;
        $kardex->cantidadEntrada = $cantidadSaldoActual;
        $kardex->vuEntrada = $precioUnitarioActual;
        $kardex->vtEntrada = $cantidadSaldoActual * $precioUnitarioActual;
        $kardex->cantidadSalida = 0;
        $kardex->vuSalida = 0;
        $kardex->vtSalida = 0;
        $kardex->cantidadSaldo = $cantidadSaldo;
        $kardex->vuSaldo = $vuSaldo;
        $kardex->vtSaldo = $vtSaldo;
        $kardex->producto_id = $productoId;
        $kardex->operacion_id = $operacionTipo;
        $kardex->compra_id = $compraId;
        $kardex->almacen_id = $tiendaId;
        $kardex->save();

        return $kardex;
    }

    public function registrarTransferenciaAlmacen() {}
}
