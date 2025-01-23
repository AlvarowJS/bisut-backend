<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_completo',
        'rfc',
        'direccion',
        'colonia',
        'delegacion',
        'estado',
        'cp',
        'telefono',
        'limite_credito',
        'dias_credito',
        'tipo_venta',
        'mail',
        'fecha_nac',
        'tipo_cliente_id',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_email'


    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function tipoCliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }
}
