<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item',
        'cantidad_venta',
        'descripcion',
        'descuento',
        'importe',
        'precio_suelto',
        'precio_venta',
        'stock',
        'total_item',
        'venta_id',
        'producto_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'venta_id' => 'integer',
        'producto_id' => 'integer',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
