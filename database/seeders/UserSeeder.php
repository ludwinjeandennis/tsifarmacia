<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin (ID 1)
        $admin = User::create([
            'name' => 'Administrador',
            'password' => '123456',
            'national_id' => '11111000011111',
            'email' => 'admin@test.com',
            'gender' => '1',
            'phone' => '01066362244',
            'date_of_birth' => fake()->date(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $admin->assignRole("admin");

        // 2. Pharmacy Owner (ID 2) - Dueño de farmacia
        $pharmacyUser = User::create([
            'name' => 'Dueño Farmacia',
            'password' => '123456',
            'national_id' => '22222000022223',
            'email' => 'pharmacy@test.com',
            'gender' => '1',
            'phone' => '01066362246',
            'date_of_birth' => fake()->date(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $pharmacyUser->assignRole("pharmacy");
        
        // 3. Doctor (ID 3)
        $doctor = User::create([
            'name' => 'Dr. Juan Pérez',
            'password' => '123456',
            'national_id' => '22222000022222',
            'email' => 'doctor_1@test.com',
            'gender' => '1',
            'phone' => '01066362245',
            'date_of_birth' => fake()->date(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $doctor->assignRole("doctor");
        
        // 4. Crear 10 usuarios clientes (para pedidos)
        for ($i = 1; $i <= 10; $i++) {
            $cliente = User::create([
                'name' => fake()->name(),
                'password' => '123456',
                'national_id' => fake()->unique()->numerify('########'),
                'email' => fake()->unique()->safeEmail(),
                'gender' => fake()->randomElement(['1', '2']), // CORREGIDO: fake() no $this->faker
                'phone' => fake()->numerify('9########'),
                'date_of_birth' => fake()->date(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
            $cliente->assignRole("client");
        }
    }
}