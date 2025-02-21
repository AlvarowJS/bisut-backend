<?php

namespace App\Traits;

use App\Models\Familia;
use App\Models\Grupo;
use App\Models\Marca;

trait DependenciaTrait
{
    public function familia($nombre)
    {

        $familiaData = Familia::where('nombre', $nombre)->first();
        if (!$familiaData) {
            $familia = new Familia();
            $familia->nombre = $nombre;
            $familia->descripcion = $nombre;
            $familia->save();
            return $familia->id;
        }
        return $familiaData->id;
    }

    public function grupo($nombre)
    {
        $grupoData = Grupo::where('nombre', $nombre)->first();
        if (!$grupoData) {
            $grupo = new Grupo();
            $grupo->nombre = $nombre;
            $grupo->descripcion = $nombre;
            $grupo->save();
            return $grupo->id;
        }
        return $grupoData->id;
    }

    public function marca($nombre)
    {
        $marcaData = Marca::where('nombre', $nombre)->first();
        if (!$marcaData) {
            $marca = new Marca();
            $marca->nombre = $nombre;
            $marca->descripcion = $nombre;
            $marca->save();
            return $marca->id;
        }
        return $marcaData->id;
    }
}
