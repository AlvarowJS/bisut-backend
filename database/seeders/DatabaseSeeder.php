<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\Role::factory()->create([
            'role_number' => 1,
            'name' => 'Administrador',
            'description' => 'Administrador',
        ]);
        \App\Models\Role::factory()->create([
            'role_number' => 2,
            'name' => 'Usuario',
            'description' => 'Usuario',
        ]);
        \App\Models\Role::factory()->create([
            'role_number' => 3,
            'name' => 'Secretaria',
            'description' => 'Secretaria',
        ]);
        \App\Models\Role::factory()->create([
            'role_number' => 4,
            'name' => 'Bodeguero',
            'description' => 'Bodeguero',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Alvaro',
            'email' => 'alvaro@gmail.com',
            'password' => 'dragonball',
            'role_id' => 1,
            'phone' => '993340954',
            'status' => 1
        ]);

        \App\Models\Operacion::factory()->create([
            'nombre' => 'Stock Inicial',
            'descripcion' => 'Primera vez que se registra',            
        ]);
        \App\Models\Operacion::factory()->create([
            'nombre' => 'Compras',
            'descripcion' => 'Cuando ya se registro varias veces',            
        ]);
        \App\Models\Operacion::factory()->create([
            'nombre' => 'Ventas',
            'descripcion' => 'Cuando ya se vendio varias veces',
        ]);
        \App\Models\Operacion::factory()->create([
            'nombre' => 'Transferencia',
            'descripcion' => 'Cuando se transfiere de una tienda a otra',
        ]);
    }
}   
