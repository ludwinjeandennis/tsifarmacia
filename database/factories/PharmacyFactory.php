<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pharmacy>
 */
class PharmacyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Nombres reales de farmacias y boticas en Puno
        $farmaciasPuno = [
            "Inkafarma", 
            "Boticas Fasa", 
            "Mifarma", 
            "Boticas Arcángel",
            "Farmacia Puno", 
            "Botica San Carlos", 
            "Botica El Sol", 
            "Farmacia Titicaca", 
            "Botica La Salud", 
            "Farmacia Los Andes",
            "Botica San Martín", 
            "Farmacia Bellavista", 
            "Botica Miraflores",
            "Farmacia San Juan", 
            "Botica La Esperanza", 
            "Farmacia Central",
        ];

        // Prioridades realistas (1 = alta, 2 = media, 3 = baja)
        $prioridades = [1, 1, 2, 2, 2, 3, 3];
        
        // Determinar si es 24h (20% probabilidad)
        $es24Horas = $this->faker->boolean(20);
        $prioridad = $es24Horas ? 1 : $prioridades[array_rand($prioridades)];
        
        // Si es 24h, agregar indicador al nombre
        $nombreBase = $farmaciasPuno[array_rand($farmaciasPuno)];
        $nombreCompleto = $es24Horas ? "{$nombreBase} (24H)" : $nombreBase;

        return [
            'name' => $nombreCompleto,
            'area_id' => $this->faker->numberBetween(1, 10), // 10 áreas creadas por AreaSeeder
            'owner_id' => 2, // USAR SOLO EL ID 2 (el usuario pharmacy que se crea en UserSeeder)
            'priority' => $prioridad,
        ];
    }
}