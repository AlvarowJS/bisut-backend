<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;

class DetalleCompraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DetalleCompra::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item' => $this->faker->word(),
            'descripcion' => $this->faker->word(),
            'cajas' => $this->faker->word(),
            'precio_unitario' => $this->faker->word(),
            'producto_id' => Producto::factory(),
            'compra_id' => Compra::factory(),
        ];
    }
}
