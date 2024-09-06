<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item',
        'descripcion',
        'precio1',
        'precio2',
        'precio3',
        'precioUnitario',
        'precioLista',
        'precioSuelto',
        'precioEspecial',
        'piezasPaquete',
        'foto'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function familia()
    {
        return $this->belongsTo(Familia::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }
}
