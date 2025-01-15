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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->string('identificador');
            $table->string('medio_pago');
            $table->string('medio_pago_monto');
            $table->string('importe');
            $table->string('descuento');
            $table->string('subTotal');
            $table->string('iva');
            $table->string('flete');
            $table->string('total');
            $table->string('puntos');
            $table->boolean('regalo');
            $table->string('tipo_factura');
            $table->string('tipo_pago');
            $table->string('modo_pago');
            $table->string('cfdi');
            $table->date('fecha');
            $table->time('hora');
            $table->foreignId('almacen_id');
            $table->foreignId('user_id');
            $table->foreignId('cliente_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
