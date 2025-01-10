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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('cantidad_venta');
            $table->string('descripcion');
            $table->string('descuento');
            $table->string('importe');
            $table->string('precio_suelto');
            $table->string('precio_venta');
            $table->string('stock');
            $table->string('total_item');                        
            $table->foreignId('producto_id')->nullable()->constrained('productos');
            $table->foreignId('venta_id')->nullable()->constrained('ventas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
