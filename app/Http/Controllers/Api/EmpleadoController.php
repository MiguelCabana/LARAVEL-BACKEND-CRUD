<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Support\Facades\Validator;

class EmpleadoController extends Controller
{
    //
    public function index()
    {
        $empleado = Empleado::all();

        if($empleado->isEmpty()) {
            $data = [
                'message' => 'No hay empleados registrados',
                'status' => 200,
            ];
            return response()->json($data,200);
        }

        $data = [
            'empleados' => $empleado,
            'status' => 2002
        ];
        return response()->json($data,200);
    }

    public function store(Request $request)
    {
        $validator = validator::make($request->all(),[

            'primer_apellido' => 'required|max:20',
            'segundo_apellido' => 'required|max:20',
            'primer_nombre' => 'required|max:20',
            'otros_nombres' => 'required|max:50',
            'pais_empleo' => 'required',
            'tipo_identificacion' => 'required',
            'numero_identificacion' => 'required|max:20|unique:empleados',
            'correo_electronico' => 'email|max:300|unique:empleados',
            'fecha_ingreso' => 'required',
            'area' => 'required',
            'estado' => 'max:20',
            'fecha_hora' => 'date_format:d-m-Y H:m:s'
        ]);

        if($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }

        // Obtener el dominio según el país
        $dominio = $request->pais_empleo == 'Colombia' ? 'global.com.co' : 'global.com.us';

        // Generar el correo electrónico con el formato especificado
        $correo_electronico_base = strtolower($request->primer_nombre. "." . $request->primer_apellido);
        $correo_electronico = $correo_electronico_base . '@' . $dominio;

        // Verificar si el correo electrónico ya existe en la base de datos
        $existing_emails_count = Empleado::where('correo_electronico',$correo_electronico)->count();

        // Si ya existe, agregar un valor numérico adicional secuencial (ID)
        if($existing_emails_count > 0) {
            $correo_electronico .= $existing_emails_count + 1; // Agregar el ID secuencial
        }

        $empleado = Empleado::create([
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'primer_nombre' => $request->primer_nombre,
            'otros_nombres' => $request->otros_nombres,
            'pais_empleo' => $request->pais_empleo,
            'tipo_identificacion' => $request->tipo_identificacion,
            'numero_identificacion' => $request->numero_identificacion,
            'correo_electronico' => $correo_electronico,
            'fecha_ingreso' => $request->fecha_ingreso,
            'area' => $request->area,
            'estado' => 'activo',
            'fecha_hora' => now(),
        ]);

        if(!$empleado) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => '500'
            ];
            return response()->json($data,500);
        }

        $data = [
            'empleados' => $empleado,
            'status' => 201
        ];

        return response()->json($data,201);
    }

    public function show ($id)
    {
        $empleado = Empleado::find($id);

        if(!$empleado) {
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $data = [
            'empleados' => $empleado,
            'status' => 200
        ];
        return response()->json($data,200);
    }

    public function destroy ($id)
    {
        $empleado = Empleado::find($id);

        if(!$empleado) {
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }
        
        $empleado->delete();

        $data = [
            'message' => 'Empleado eliminado',
            'status' => 200
        ];

        return response()->json($data,200);
    }

    public function update(Request $request, $id) 
    {
        $empleado = Empleado::find($id);

        if(!$empleado) {
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $validator = validator::make($request->all(),[

            'primer_apellido' => 'required|max:20',
            'segundo_apellido' => 'required|max:20',
            'primer_nombre' => 'required|max:20',
            'otros_nombres' => 'required|max:50',
            'pais_empleo' => 'required',
            'tipo_identificacion' => 'required',
            'numero_identificacion' => 'required|max:20',
            'correo_electronico' => 'required|email|max:300',
            'fecha_ingreso' => 'required',
            'area' => 'required',

        ]);

        if($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => '400'
            ];
            return response()->json($data,400);
        }


        // Obtener el dominio según el país
        $dominio = $request->pais_empleo === 'Colombia' ? 'global.com.co' : 'global.com.us' ;
        //Generar nuevo correo electronico con los datos actualizado PRIMER_NOMBRE - PRIMER_APELLIDO
        $correo_electronico_base = strtolower($request->primer_nombre . "." . $request->primer_apellido);
        $correo_electronico = $correo_electronico_base . "@" . $dominio;


        $empleado->primer_apellido = $request->primer_apellido;
        $empleado->segundo_apellido = $request->segundo_apellido;
        $empleado->primer_nombre = $request->primer_nombre;
        $empleado->otros_nombres = $request->otros_nombres;
        $empleado->pais_empleo = $request->pais_empleo;
        $empleado->tipo_identificacion = $request->tipo_identificacion;
        $empleado->numero_identificacion = $request->numero_identificacion;
        $empleado->correo_electronico = $correo_electronico;
        $empleado->fecha_ingreso = $request->fecha_ingreso;
        $empleado->area = $request->area;


        $empleado->save();

        $data = [
            'message' => 'Empleado actualizado',
            'empleados' => $empleado,
            'status' => 200
        ];

        return response()->json($data,200);
    }

    public function updatePartial(Request $request, $id)
    {
        $empleado = Empleado::find($id);

        if(!$empleado) {
            $data = [
                'message' => 'Empleado no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $validator = validator::make($request->all(),[

            'primer_apellido' => 'max:20',
            'segundo_apellido' => 'max:20',
            'primer_nombre' => 'max:20',
            'otros_nombres' => 'max:50',
            'pais_empleo' => '',
            'tipo_identificacion' => '',
            'numero_identificacion' => 'max:20',
            'correo_electronico' => 'email|max:300',
            'fecha_ingreso' => '',
            'area' => '',
            'estado' => '',
            'fecha_hora' => ''
        ]);

        if($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => '400'
            ];
            return response()->json($data,400);
        }

        if($request->has('primer_apellido')) {
            $empleado->primer_apellido = $request->primer_apellido;
        }

        if($request->has('segundo_apellido')) {
            $empleado->segundo_apellido = $request->segundo_apellido;
        }

        if($request->has('primer_nombre')) {
            $empleado->primer_nombre = $request->primer_nombre;
        }

        if($request->has('otros_nombres')) {
            $empleado->otros_nombres = $request->otros_nombres;
        }

        if($request->has('pais_empleo')) {
            $empleado->pais_empleo = $request->pais_empleo;
        }

        if($request->has('tipo_identificacion')) {
            $empleado->tipo_identificacion = $request->tipo_identificacion;
        }

        if($request->has('numero_identificacion')) {
            $empleado->numero_identificacion = $request->numero_identificacion;
        }

        if($request->has('correo_electronico')) {
            $empleado->correo_electronico = $request->correo_electronico;
        }

        if($request->has('fecha_ingreso')) {
            $empleado->fecha_ingreso = $request->fecha_ingreso;
        }

        if($request->has('area')) {
            $empleado->area = $request->area;
        }

        if($request->has('estado')) {
            $empleado->estado = $request->estado;
        }

        if($request->has('fecha_hora')) {
            $empleado->fecha_hora = $request->fecha_hora;
        }

        $empleado->save();

        $data = [
            'message' => 'Empleado actualizado',
            'empleados' => $empleado,
            'status' => 200
        ];

        return response()->json($data,200);

    }
}


