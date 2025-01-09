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
            $table->string('documento')->nullable();
            $table->string('cantidadEntrada')->nullable();
            $table->string('vuEntrada')->nullable();
            $table->string('vtEntrada')->nullable();
            $table->string('cantidadSalida')->nullable();
            $table->string('vuSalida')->nullable();
            $table->string('vtSalida')->nullable();
            $table->string('cantidadSaldo')->nullable();
            $table->string('vuSaldo')->nullable();
            $table->string('vtSaldo')->nullable();
            $table->foreignId('producto_id')->nullable()->constrained('productos');
            $table->foreignId('operacion_id')->nullable()->constrained('operacions');
            $table->foreignId('compra_id')->nullable()->constrained('compras');
            $table->foreignId('venta_id')->nullable()->constrained('ventas');
            $table->foreignId('almacen_id')->nullable()->constrained('almacens');
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
