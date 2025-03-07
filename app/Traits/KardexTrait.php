<?php

namespace App\Traits;

use App\Models\Kardex;
use Exception;

use function Laravel\Prompts\error;

trait KardexTrait
{
    public function conversionDocenasAPiezasEmisor(
        $productoIdEmisor,
        $cajas,
        $almacenId,
        $fecha
    ) {

        $ultimoKardex = Kardex::where('producto_id', $productoIdEmisor)
            ->where('almacen_id', $almacenId)
            ->orderBy('id', 'desc')
            ->first();

        // Si no hay registros previos, asumimos que no hay stock (evitar saldos negativos)
        $cantidadSaldoAnterior = $ultimoKardex ? $ultimoKardex->cantidadSaldo : 0;
        $vuSaldoAnterior = $ultimoKardex ? $ultimoKardex->vuSaldo : 0;
        $vtSaldoAnterior = $ultimoKardex ? $ultimoKardex->vtSaldo : 0;

        // Evitar que la cantidad de saldo sea negativa
        if ($cantidadSaldoAnterior < $cajas) {
            throw new Exception("Stock insuficiente para la salida de $cajas cajas.");
        }

        // Calcular el nuevo saldo después de la salida
        $nuevaCantidadSaldo = $cantidadSaldoAnterior - $cajas;
        $nuevoVtSaldo = $nuevaCantidadSaldo * $vuSaldoAnterior; // Manteniendo el costo promedio
        $nuevoVuSaldo = $nuevaCantidadSaldo > 0 ? $nuevoVtSaldo / $nuevaCantidadSaldo : 0;

        // Crear nuevo registro en el Kardex
        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        $kardex->cantidadEntrada = 0;
        $kardex->vuEntrada = 0;
        $kardex->vtEntrada = 0;
        $kardex->cantidadSalida = $cajas;
        $kardex->vuSalida = $vuSaldoAnterior; // Usar el costo promedio actual
        $kardex->vtSalida = $cajas * $vuSaldoAnterior;
        $kardex->cantidadSaldo = $nuevaCantidadSaldo;
        $kardex->vuSaldo = $nuevoVuSaldo;
        $kardex->vtSaldo = $nuevoVtSaldo;
        $kardex->producto_id = $productoIdEmisor;
        $kardex->operacion_id = 5;
        $kardex->almacen_id = $almacenId;
        $kardex->save();
    }
    public function conversionDocenasAPiezasReceptor(
        $productoIdReceptor,
        $cantidadPiezas,
        $almacenId,
        $fecha
    ) {
        // Obtener el último registro del Kardex para el producto en el almacén
        $ultimoKardex = Kardex::where('producto_id', $productoIdReceptor)
            ->where('almacen_id', $almacenId)
            ->orderBy('fecha', 'desc')
            ->first();

        // Si no hay registros previos, asumimos que el saldo es 0
        $cantidadSaldoAnterior = $ultimoKardex ? $ultimoKardex->cantidadSaldo : 0;
        $vuSaldoAnterior = $ultimoKardex ? $ultimoKardex->vuSaldo : 0;
        $vtSaldoAnterior = $ultimoKardex ? $ultimoKardex->vtSaldo : 0;

        // Calcular el nuevo saldo
        $nuevaCantidadSaldo = $cantidadSaldoAnterior + $cantidadPiezas;
        $nuevoVtSaldo = $vtSaldoAnterior; // No cambia si es conversión interna
        $nuevoVuSaldo = $nuevaCantidadSaldo > 0 ? $nuevoVtSaldo / $nuevaCantidadSaldo : 0;

        // Crear el registro en el Kardex
        $kardex = new Kardex();
        $kardex->fecha = $fecha;
        $kardex->cantidadEntrada = $cantidadPiezas;
        $kardex->vuEntrada = $vuSaldoAnterior; // Mantener el costo promedio
        $kardex->vtEntrada = $cantidadPiezas * $vuSaldoAnterior;
        $kardex->cantidadSalida = 0;
        $kardex->vuSalida = 0;
        $kardex->vtSalida = 0;
        $kardex->cantidadSaldo = $nuevaCantidadSaldo;
        $kardex->vuSaldo = $nuevoVuSaldo;
        $kardex->vtSaldo = $nuevoVtSaldo;
        $kardex->producto_id = $productoIdReceptor;
        $kardex->operacion_id = 5;
        $kardex->almacen_id = $almacenId;
        $kardex->save();
    }

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
        if ($cantidadSaldo < 0) {
            throw new Exception("No hay suficiente stock en el almacén $tiendaId para el producto ID: $productoId.");
        }
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
            $vtSaldo = $cantidad * $vuSaldoAnterior;
        } else {
            $cantidadSaldo = $operacion->cantidadSaldo + $cantidad;
            $vtSaldo = $operacion->vtSaldo + ($cantidad * $operacion->vuSaldo);
            $vuSaldo = $vtSaldo / $cantidadSaldo;
        }
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

        try {
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
        } catch (Exception $e) {
            throw new Exception("Error en la transferencia de producto: " . $e->getMessage());
        }
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
            $vtSaldo = $cantidadSaldoActual * $precioUnitarioActual;
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
