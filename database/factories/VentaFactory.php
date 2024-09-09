<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Venta;

class VentaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Venta::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'importe' => $this->faker->word(),
            'descuento' => $this->faker->word(),
            'subTotal' => $this->faker->word(),
            'iva' => $this->faker->word(),
            'flete' => $this->faker->word(),
            'total' => $this->faker->word(),
            'almacen_id' => Almacen::factory(),
            'user_id' => User::factory(),
            'cliente_id' => Cliente::factory(),
        ];
    }
}
