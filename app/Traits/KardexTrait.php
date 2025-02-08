<?php

namespace App\Traits;

use App\Models\Kardex;

trait KardexTrait
{
    public function transferirProductoKardex(
        $productoId,
        $tiendaId,
        $fecha,
        $cantidad,        
        $tipo
    ) {
        //tipo 1 emisior
        //tipo 2 receptor
        $operacion = Kardex::where('producto_id', $productoId)
            ->where('almacen_id', $tiendaId)
            ->latest('id')
            ->first();

        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        // ventas
        if ($tipo == 1) {
            // operaciones:
            $cantidadSaldo = $operacion->cantidadSaldo - $cantidad;
            $vtSaldo = $operacion->vtSaldo - ($operacion * $operacion->vuSaldo);
            $vuSaldo = $vtSaldo / $cantidadSaldo;
            //
            $kardex->cantidadEntrada = 0;
            $kardex->vuEntrada = 0;
            $kardex->vtEntrada = 0;
            $kardex->cantidadSalida = $cantidad;
            $kardex->vuSalida = $operacion->vuSaldo;
            $kardex->vtSalida = $cantidad * $operacion->vuSaldo;

            $kardex->cantidadSaldo = $cantidadSaldo;
            $kardex->vtSaldo = $vtSaldo;
            $kardex->vuSaldo = $vuSaldo;
        } else {
            $kardex->cantidadEntrada = $cantidad;
            $kardex->vuEntrada = $operacion->vuSaldo;
            $kardex->vtEntrada = $cantidad * $operacion->vuSaldo;
            $kardex->cantidadSalida = 0;
            $kardex->vuSalida = 0;
            $kardex->vtSalida = 0;

            // operaciones:
            $cantidadSaldo = $operacion->cantidadSaldo + $cantidad;
            $vtSaldo = $operacion->vtSaldo + ($cantidad * $operacion->vuSaldo);
            $vuSaldo = $vtSaldo / $cantidadSaldo;
            //
            $kardex->cantidadSaldo = $cantidadSaldo;
            $kardex->vuSaldo = $vuSaldo;
            $kardex->vtSaldo = $vtSaldo;
        }
        $kardex->producto_id = $productoId;
        $kardex->operacion_id = 4;
        $kardex->almacen_id = $tiendaId;
        $kardex->save();
    }
    public function registrarVenta(
        $productoId,
        $tiendaId,
        $ventaId,
        $fecha,
        $factura,
        $cantidadSaldoActual,
        $precioUnitarioActual
    ) {
        $operacion = Kardex::where('producto_id', $productoId)
            ->where('almacen_id', $tiendaId)
            ->latest('id')
            ->first();

        $operacionTipo = 3;

        if ($operacionTipo == 3) {
            $cantidadSaldo = $operacion->cantidadSaldo - $cantidadSaldoActual;
            $vtSaldo = $operacion->vtSaldo - ($cantidadSaldoActual * $precioUnitarioActual);
            $vuSaldo = $vtSaldo / $cantidadSaldo;
        } else {
            return null;
        }

        // Crear el registro en el Kardex
        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        $kardex->documento = $factura;
        $kardex->cantidadEntrada = 0;
        $kardex->vuEntrada = 0;
        $kardex->vtEntrada = 0;
        $kardex->cantidadSalida = $cantidadSaldoActual;
        $kardex->vuSalida = $precioUnitarioActual;
        $kardex->vtSalida = $cantidadSaldoActual * $precioUnitarioActual;
        $kardex->cantidadSaldo = $cantidadSaldo;
        $kardex->vuSaldo = $vuSaldo;
        $kardex->vtSaldo = $vtSaldo;
        $kardex->producto_id = $productoId;
        $kardex->operacion_id = $operacionTipo;
        $kardex->venta_id = $ventaId;
        $kardex->almacen_id = $tiendaId;
        $kardex->save();

        return $kardex;
    }

    public function registrarCompra(
        $productoId,
        $tiendaId,
        $compraId,
        $fecha,
        $factura,
        $cantidadSaldoActual,
        $precioUnitarioActual
    ) {
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
            $vuSaldo = $vtSaldo / $cantidadSaldo;
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
