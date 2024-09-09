<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DetalleVenta;
use App\Models\Producto;

class DetalleVentaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetalleVenta::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'cantidad_venta' => $this->faker->word(),
            'precio_venta' => $this->faker->word(),
            'producto_id' => Producto::factory(),
        ];
    }
}
