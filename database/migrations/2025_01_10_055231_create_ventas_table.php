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
            $table->string('importe');
            $table->string('descuento');
            $table->string('subTotal');
            $table->string('iva');
            $table->string('flete');
            $table->string('total');
            $table->string('puntos');
            $table->boolean('regalo');
            $table->string('tipo_factura');
            $table->string('modo_pago');
            $table->string('tipo_pago');
            $table->string('cfdi');
            $table->date('fecha');
            $table->foreignId('almacen_id')->nullable()->constrained('almacens');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');            
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
