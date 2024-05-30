<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {

            $table->id();
            // Definir el campo 'primer_apellido' con una longitud máxima de 20 caracteres
            $table->string('primer_apellido', 20);
            // Definir el campo 'segundo_apellido' con una longitud máxima de 20 caracteres
            $table->string('segundo_apellido', 20);
            // Definir el campo 'primer_nombre' con una longitud máxima de 20 caracteres
            $table->string('primer_nombre');
            // Definir el campo 'otros_nombres' con una longitud máxima de 50 caracteres
            $table->string('otros_nombres', 50);
            // Definir el campo 'pais_empleo' 
            $table->string('pais_empleo');
            // Definir el campo 'tipo_identificacion' 
            $table->string('tipo_identificacion');
            // Definir el campo 'numero_identificacion' con una longitud máxima de 20 caracteres
            $table->string('numero_identificacion', 20);
            // Definir el campo 'correo_electronico' con una longitud máxima de 300 caracteres (Generar automaticamente)
            $table->string('correo_electronico',300);
            // Definir el campo 'fecha_ingreso' 
            $table->date('fecha_ingreso');  
            // Definir el campo 'area'
            $table->string('area');   
            // Definir el campo 'estado' por defecto activo
            $table->string('estado')->default('activo');   
            // Definir el campo 'fecha_hora' no modificable DD/MM/YYYY HH:mm:ss
            $table->datetime('fecha_hora')->default(now());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
