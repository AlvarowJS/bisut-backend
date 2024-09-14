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
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('descripcion');
            $table->string('cantidad')->nullable();
            $table->float('precio_unitario')->nullable();
            $table->float('total')->nullable();
            $table->foreignId('producto_id')->nullable()->constrained('productos');
            $table->foreignId('compra_id')->nullable()->constrained('compras');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compras');
    }
};
