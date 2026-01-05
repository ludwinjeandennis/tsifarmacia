<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Estados
        $probabilidad = fake()->randomFloat(2, 0, 1);
        
        if ($probabilidad <= 0.60) {
            $estado = 4; // Entregado
        } elseif ($probabilidad <= 0.80) {
            $estado = fake()->randomElement([2, 3]);
        } elseif ($probabilidad <= 0.90) {
            $estado = 1;
        } else {
            $estado = 5;
        }

        // Fechas
        $creado = fake()->dateTimeBetween('-60 days', 'now');
        
        if ($estado === 4 || $estado === 5) {
            $actualizado = fake()->dateTimeBetween($creado, 'now');
        } else {
            $actualizado = $creado;
        }

        return [
            'status' => $estado,
            'is_insured' => fake()->boolean(70) ? 1 : 0,
            'pharmacy_id' => 1, // Farmacia ID 1 (la que crea PharmacySeeder)
            'user_id' => fake()->numberBetween(4, 13), // Usuarios clientes (IDs 4-13)
            'doctor_id' => 3, // Doctor ID 3
            'created_at' => $creado,
            'updated_at' => $actualizado,
            'deleted_at' => $estado === 5 ? $actualizado : null,
        ];
    }
}