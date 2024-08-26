<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Producto;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'item' => $this->faker->word(),
            'descripcion' => $this->faker->word(),
            'precio1' => $this->faker->word(),
            'precio2' => $this->faker->word(),
            'precio3' => $this->faker->word(),
            'precioUnitario' => $this->faker->word(),
            'precioLista' => $this->faker->word(),
            'precioSuelto' => $this->faker->word(),
            'precioEspecial' => $this->faker->word(),
            'piezasPaquete' => $this->faker->word(),
        ];
    }
}
