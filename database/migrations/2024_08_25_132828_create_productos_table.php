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
            $table->string('item')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('cantidadxCaja')->nullable();
            $table->float('precio1')->nullable();
            $table->float('precio2')->nullable();
            $table->float('precio3')->nullable();
            $table->float('precio4')->nullable();            
            $table->float('precioSuelto')->nullable();            
            $table->float('piezasPaquete')->nullable();
            $table->string('fiscal')->nullable();
            $table->integer('cajas')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('unidad')->nullable();
            $table->string('tono')->nullable();
            $table->integer('minimo')->nullable();
            $table->integer('maximo')->nullable();
            $table->text('foto')->nullable();
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
