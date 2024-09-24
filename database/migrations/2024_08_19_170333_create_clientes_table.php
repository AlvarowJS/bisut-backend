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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo')->nullable();
            $table->string('rfc')->nullable();
            $table->string('direccion')->nullable();
            $table->string('colonia')->nullable();
            $table->string('delegacion')->nullable();
            $table->string('estado')->nullable();
            $table->string('cp')->nullable();
            $table->string('telefono')->nullable();
            $table->float('limite_credito')->nullable();
            $table->integer('dias_credito')->nullable();
            $table->string('mail')->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('contacto_nombre')->nullable();
            $table->string('contacto_telefono')->nullable();
            $table->string('contacto_email')->nullable();
            $table->string('tipo')->nullable();
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
