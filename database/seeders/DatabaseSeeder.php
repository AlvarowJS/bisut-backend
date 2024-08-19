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
            'name' => 'vendedor',
            'description' => 'vendedor',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Alvaro',
            'email' => 'alvaro@gmail.com',
            'password' => 'dragonball',
            'role_id' => 1,
            'phone' => '993340954'

        ]);
    }
}   
