<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait GuardaImagenTrait
{
    public function guardarImagen($carpeta, $nombreAtributo, $fotoInput, $request)
    {
        $nombre = '';
        $ruta = public_path($carpeta);
        if (!File::isDirectory($ruta)) {
            $publicpath = 'storage/' . $carpeta;
            File::makeDirectory($publicpath, 0777, true, true);
        }
        $imagen = $request->file($fotoInput);
        if ($request->hasFile($fotoInput)) {
            $nombre = uniqid() . '.' . $imagen->getClientOriginalName();
            $path = $carpeta . '/' . $nombreAtributo . '/' . $nombre;
            Storage::disk('public')->put($path, File::get($imagen));
        }

        return $nombre;
    }

    public function actualizarImagen($carpeta, $fotoAntigua, $nombreAntiguo, $nombreAtributo, $fotoInput, $request)
    {
        if ($nombreAtributo) {
            if (Storage::disk('public')->exists($carpeta . '/' . $nombreAntiguo)) {
                Storage::disk('public')->move($carpeta . '/' . $nombreAntiguo, $carpeta . '/' . $nombreAtributo);
            }
        }
        if ($request->hasFile($fotoInput)) {
            if ($fotoAntigua) {
                Storage::disk('public')->delete($carpeta . '/' . $nombreAntiguo . '/' . $fotoAntigua);
            }

            $imagen = $request->file($fotoInput);
            $nombre = uniqid() . '.' . $imagen->getClientOriginalName();
            $path = $carpeta . '/' . $nombreAtributo . '/' . $nombre;
            Storage::disk('public')->put($path, File::get($imagen));
            return $nombre;
        }
    }

    public function eliminarImagen($carpeta, $nombreAtributo, $fotoAntigua)
    {
        Storage::disk('public')->delete($carpeta . '/' . $nombreAtributo . '/' . $fotoAntigua);
    }
}
