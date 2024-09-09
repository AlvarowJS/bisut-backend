<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Almacen;
use App\Models\Compra;
use App\Models\Kardex;
use App\Models\Operacion;
use App\Models\Producto;
use App\Models\Venta;

class KardexFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kardex::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'fecha' => $this->faker->date(),
            'documento' => $this->faker->word(),
            'cantidadEntrada' => $this->faker->word(),
            'vuEntrada' => $this->faker->word(),
            'vtEntrada' => $this->faker->word(),
            'cantidadSalida' => $this->faker->word(),
            'vuSalida' => $this->faker->word(),
            'vtSalida' => $this->faker->word(),
            'CantidadSaldo' => $this->faker->word(),
            'vuSaldo' => $this->faker->word(),
            'vtSaldo' => $this->faker->word(),
            'producto_id' => Producto::factory(),
            'operacion_id' => Operacion::factory(),
            'compra_id' => Compra::factory(),
            'venta_id' => Venta::factory(),
            'almacen_id' => Almacen::factory(),
        ];
    }
}
