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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('descripcion');
            $table->float('precio1');
            $table->float('precio2');
            $table->float('precio3');
            $table->float('precioUnitario');
            $table->float('precioLista');
            $table->float('precioSuelto');
            $table->float('precioEspecial');
            $table->integer('piezasPaquete');
            $table->foreignId('familia_id')->nullable()->constrained('familias');
            $table->foreignId('grupo_id')->nullable()->constrained('grupos');
            $table->foreignId('marca_id')->nullable()->constrained('marcas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
