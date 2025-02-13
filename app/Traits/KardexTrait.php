<?php

namespace App\Traits;

use App\Models\Kardex;

trait KardexTrait
{
    public function registrarSalidaTrasnferencia(
        $fecha,
        $cantidad,
        $tiendaId,
        $productoId,
        $cantidadSaldoAnterior,
        $vuSaldoAnterior,
        $vtSaldoAnterior
    ) {
        $cantidadSaldo = $cantidadSaldoAnterior - $cantidad;
        $vtSaldo = $vtSaldoAnterior - ($cantidad * $vuSaldoAnterior);
        $vuSaldo = $vtSaldo / $cantidadSaldo;
        
        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        //
        $kardex->cantidadEntrada = 0;
        $kardex->vuEntrada = 0;
        $kardex->vtEntrada = 0;
        $kardex->cantidadSalida = $cantidad;
        $kardex->vuSalida = $vuSaldoAnterior;
        $kardex->vtSalida = $cantidad * $vuSaldoAnterior;

        $kardex->cantidadSaldo = $cantidadSaldo;
        $kardex->vtSaldo = $vtSaldo;
        $kardex->vuSaldo = $vuSaldo;
        $kardex->producto_id = $productoId;
        $kardex->operacion_id = 4;
        $kardex->almacen_id = $tiendaId;
        $kardex->save();
    }
    public function registrarEntradaTransferencia(
        $fecha,
        $cantidad,
        $tiendaId,
        $productoId,
        $cantidadSaldoAnterior,
        $vuSaldoAnterior,
        $vtSaldoAnterior
    ) {
        $operacion = Kardex::where('producto_id', $productoId)
            ->where('almacen_id', $tiendaId)
            ->latest('id')
            ->first();

        $operacionTipo = $operacion ? 2 : 1;

        if ($operacionTipo == 1) {
            $cantidadSaldo = $cantidad;
            $vuSaldo = $vuSaldoAnterior;
            $vtSaldo = $cantidad + $vuSaldoAnterior;
        } else {
            $cantidadSaldo = $operacion->cantidad + $cantidad;
            $vtSaldo = $operacion->vtSaldo + ($cantidad * $operacion->vuSaldo);
            $vuSaldo = $vtSaldo / $cantidadSaldo;
        }
        echo($operacion ? $operacion->vuSaldo : $vuSaldoAnterior);
        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        $kardex->cantidadEntrada = $cantidad;
        $kardex->vuEntrada = $operacion ? $operacion->vuSaldo : $vuSaldoAnterior;
        $kardex->vtEntrada = $cantidad * ($operacion ? $operacion->vuSaldo : $vuSaldoAnterior);
        $kardex->cantidadSalida = 0;
        $kardex->vuSalida = 0;
        $kardex->vtSalida = 0;
        $kardex->cantidadSaldo = $cantidadSaldo;
        $kardex->vuSaldo = $vuSaldo;
        $kardex->vtSaldo = $vtSaldo;
        $kardex->producto_id = $productoId;
        $kardex->operacion_id = 4;
        $kardex->almacen_id = $tiendaId;
        $kardex->save();
    }
    public function transferirProductoKardex(
        $productoId,
        $tiendaIdEmisor,
        $tiendaIdReceptor,
        $fecha,
        $cantidad
    ) {
        $operacion = Kardex::where('producto_id', $productoId)
            ->where('almacen_id', $tiendaIdEmisor)
            ->latest('id')
            ->first();
        $this->registrarSalidaTrasnferencia(
            $fecha,
            $cantidad,
            $tiendaIdEmisor,
            $productoId,
            $operacion->cantidadSaldo,
            $operacion->vuSaldo,
            $operacion->vtSaldo
        );
        $this->registrarEntradaTransferencia(
            $fecha,
            $cantidad,
            $tiendaIdReceptor,
            $productoId,
            $operacion->cantidadSaldo,
            $operacion->vuSaldo,
            $operacion->vtSaldo
        );
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
