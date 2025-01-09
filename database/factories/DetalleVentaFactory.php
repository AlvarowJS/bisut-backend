<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;

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
            'item' => $this->faker->word(),
            'cantidad_venta' => $this->faker->word(),
            'descripcion' => $this->faker->word(),
            'descuento' => $this->faker->word(),
            'importe' => $this->faker->word(),
            'precio_suelto' => $this->faker->word(),
            'precio_venta' => $this->faker->word(),
            'stock' => $this->faker->word(),
            'total_item' => $this->faker->word(),
            'venta_id' => Venta::factory(),
            'producto_id' => Producto::factory(),
        ];
    }
}
