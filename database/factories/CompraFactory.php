<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;
use App\Models\Compra;

class CompraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Compra::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'factura' => $this->faker->word(),
            'fecha' => $this->faker->date(),
            'total' => $this->faker->word(),
            'cliente_id' => Cliente::factory(),
        ];
    }
}
