<?php
namespace App\Traits;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
trait GuardaImagenTrait {
    public function guardarImagen($carpeta, $nombreAtributo, $fotoInput, $request)
    {
        $ruta = public_path($carpeta);
        if (!File::isDirectory($ruta)) {
            $publicpath = 'storage/' . $carpeta;
            File::makeDirectory($publicpath, 0777, true, true);
        }
        $imagen = $request->file($fotoInput);
        if ($request->hasFile($fotoInput)) {
            $nombre = uniqid() . '.' . $imagen->getClientOriginalName();
            $path = $carpeta.'/'.$nombreAtributo.'/'.$nombre;
            Storage::disk('public')->put($path, File::get($imagen));            
        }

        return $nombre;

    }
}