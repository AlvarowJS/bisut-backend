<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Cliente;

class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre_completo' => $this->faker->word(),
            'rfc' => $this->faker->word(),
            'direccion' => $this->faker->word(),
            'colonia' => $this->faker->word(),
            'delegacion' => $this->faker->word(),
            'estado' => $this->faker->word(),
            'cp' => $this->faker->word(),
            'telefono' => $this->faker->word(),
            'limite_credito' => $this->faker->word(),
            'mail' => $this->faker->word(),
        ];
    }
}
