<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identificador',
        'medio_pago',
        'medio_pago_monto',
        'importe',
        'descuento',
        'subTotal',
        'iva',
        'flete',
        'total',
        'puntos',
        'regalo',
        'tipo_factura',
        'tipo_pago',
        'modo_pago',
        'cfdi',
        'fecha',
        'hora',
        'almacen_id',
        'user_id',
        'cliente_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'regalo' => 'boolean',
        // 'fecha' => 'date',
        'almacen_id' => 'integer',
        'user_id' => 'integer',
        'cliente_id' => 'integer',
    ];

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
