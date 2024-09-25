<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\TipoCliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function indexTipos()
    {
        $data = TipoCliente::all();
        return response()->json($data);
    }
    public function index()
    {
        $dateNow = date("Y-m-d");
        $currentDate = date("m-d", strtotime($dateNow));

        $data = Cliente::orderBy("fecha_nac", "asc")->get();

        $upcomingDates = Cliente::whereRaw("DATE_FORMAT(fecha_nac, '%m-%d') >= ?", [$currentDate])
            ->orderByRaw("DATE_FORMAT(fecha_nac, '%m-%d') ASC")
            ->get();

        return response()->json([
            "data" => $data,
            "upcoming" => $upcomingDates
        ]);
    }

    public function store(Request $request)
    {
        $data = new Cliente();
        $data->nombre_completo = $request->nombre_completo;
        $data->rfc = $request->rfc;
        $data->direccion = $request->direccion;
        $data->colonia = $request->colonia;
        $data->delegacion = $request->delegacion;
        $data->estado = $request->estado;
        $data->cp = $request->cp;
        $data->telefono = $request->telefono;
        $data->limite_credito = $request->limite_credito;
        $data->mail = $request->mail;
        $data->fecha_nac = $request->fecha_nac;
        $data->dias_credito = $request->dias_credito;
        $data->contacto_nombre = $request->contacto_nombre;
        $data->contacto_telefono = $request->contacto_telefono;
        $data->contacto_email = $request->contacto_email;
        $data->tipo_cliente_id = $request->tipo_cliente_id;

        $data->save();
        return response()->json($data);
    }

    public function show(string $id) 
    {
        $data = Cliente::find($id);

        if(!$data) {
            return response()->json(['message'=> 'Registro no encontrado'],404);
        }

        return response()->json($data);
    }

    public function update(Request $request, string $id) 
    {
        $data = Cliente::find($id);
        if(!$data) {
            return response()->json(['message'=> 'Registro no encontrado'],404);
        }
        $data->nombre_completo = $request->nombre_completo;
        $data->rfc = $request->rfc;
        $data->direccion = $request->direccion;
        $data->colonia = $request->colonia;
        $data->delegacion = $request->delegacion;
        $data->estado = $request->estado;
        $data->cp = $request->cp;
        $data->telefono = $request->telefono;
        $data->limite_credito = $request->limite_credito;
        $data->mail = $request->mail;
        $data->fecha_nac = $request->fecha_nac;
        $data->dias_credito = $request->dias_credito;
        $data->contacto_nombre = $request->contacto_nombre;
        $data->contacto_telefono = $request->contacto_telefono;
        $data->contacto_email = $request->contacto_email;
        $data->tipo_cliente_id = $request->tipo_cliente_id;
        $data->save();
        return response()->json([
            'message'=> 'Registro creado',
            'data'=> $data
        ],200);


    }

    public function destroy(string $id) {
        $data = Cliente::find($id);
        if(!$data) {
            return response()->json(['message'=> 'Registro no encontrado'],404);
        }
        $data->delete();
        return response()->json(['message'=> 'Registro eliminado'],200);
    }
}
