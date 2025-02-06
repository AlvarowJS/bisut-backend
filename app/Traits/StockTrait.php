<?php

namespace App\Traits;

use App\Models\Kardex;

trait StockTrait
{
    public function verStock($tiendaId, $productoId)
    {
        $stock = Kardex::where('almacen_id', $tiendaId)
            ->where('producto_id', $productoId)
            ->orderBy('id', 'desc')
            ->first();
        if(!$stock){
            return 0;
        }
        return $stock->cantidadSaldo;
    }
}
