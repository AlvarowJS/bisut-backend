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


        \App\Models\TipoCliente::factory()->create([
            'nombre' => 'Mayorista',
        ]);
        \App\Models\TipoCliente::factory()->create([
            'nombre' => 'Venta Público',
        ]);
        \App\Models\TipoCliente::factory()->create([
            'nombre' => 'Ventas x Internet',
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

        // Probablemente se tenga que comentar:
        \App\Models\Cliente::factory()->create([
            'nombre_completo' => 'Juan Pérez',
            'rfc' => 'JUAP890101XYZ',
            'direccion' => 'Calle Falsa 123',
            'colonia' => 'Centro',
            'delegacion' => 'Cuauhtémoc',
            'estado' => 'Ciudad de México',
            'cp' => '06000',
            'telefono' => '5551234567',
            'limite_credito' => 50000.00,
            'dias_credito' => 30,
            'tipo_venta' => 1,
            'mail' => 'juan.perez@example.com',
            'fecha_nac' => '1989-01-01',
            'tipo_cliente_id' => 1,
            'contacto_nombre' => 'Maria López',
            'contacto_telefono' => '5559876543',
            'contacto_email' => 'maria.lopez@example.com'
        ]);

        \App\Models\Proveedor::factory()->create([
            'nombre' => 'Distribuidora Comercial S.A.',
            'direccion' => 'Av. Principal 456, Colonia Industrial',
            'telefono' => '5556789012',
            'mail' => 'contacto@distribuidora.com',
            'codigo_postal' => '09010',
            'estado' => true,
            'user_id' => 1,
        ]);
        

        \App\Models\Grupo::factory()->create([
            'nombre' => 'Grupo Prueba',
            'descripcion' => 'Descripcion de grupo prueba',
        ]);

        \App\Models\Marca::factory()->create([
            'nombre' => 'Marca Prueba',
            'descripcion' => 'Descripcion de marca prueba',
        ]);

        \App\Models\Familia::factory()->create([
            'nombre' => 'Familia Prueba',
            'descripcion' => 'Descripcion de familia prueba',
        ]);

        \App\Models\Almacen::factory()->create([
            'nombre' => 'Almacen Vargas',
            'direccion' => 'San diego 250',
            'telefono' => '993340954',
            'rfc' => 'CUHC7005259A7',
            'tipo' => 1
        ]);
    }
}   
