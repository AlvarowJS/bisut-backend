<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kardex extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha',
        'documento',
        'cantidadEntrada',
        'vuEntrada',
        'vtEntrada',
        'cantidadSalida',
        'vuSalida',
        'vtSalida',
        'cantidadSaldo',
        'vuSaldo',
        'vtSaldo',
        'producto_id',
        'operacion_id',
        'compra_id',
        'venta_id',
        'almacen_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'producto_id' => 'integer',
        'operacion_id' => 'integer',
        'compra_id' => 'integer',
        'venta_id' => 'integer',
        'almacen_id' => 'integer',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function operacion(): BelongsTo
    {
        return $this->belongsTo(Operacion::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }
}
