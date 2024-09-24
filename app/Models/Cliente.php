<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'mail',
        'fecha_nac',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_email',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
}
