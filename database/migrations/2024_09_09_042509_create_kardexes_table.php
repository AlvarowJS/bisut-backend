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
        Schema::create('kardexes', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('documento');
            $table->string('cantidadEntrada');
            $table->string('vuEntrada');
            $table->string('vtEntrada');
            $table->string('cantidadSalida');
            $table->string('vuSalida');
            $table->string('vtSalida');
            $table->string('CantidadSaldo');
            $table->string('vuSaldo');
            $table->string('vtSaldo');
            $table->foreignId('producto_id');
            $table->foreignId('operacion_id');
            $table->foreignId('compra_id');
            $table->foreignId('venta_id');
            $table->foreignId('almacen_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardexes');
    }
};
