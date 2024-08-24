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
            $table->string('nombre_completo');            
            $table->string('rfc');
            $table->string('direccion');
            $table->string('colonia');
            $table->string('delegacion');
            $table->string('estado');
            $table->string('cp');
            $table->string('telefono');
            $table->float('limite_credito');
            $table->string('mail');
            $table->date('fecha_nac');
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
