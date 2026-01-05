<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtener IDs de usuarios existentes
        $usuariosIds = User::pluck('id')->toArray();
        
        // Si no hay usuarios, usar un rango seguro
        if (empty($usuariosIds)) {
            $usuariosIds = range(1, 13); // IDs 1-13 según UserSeeder
        }

        // Obtener IDs de áreas existentes (creadas por AreaSeeder)
        $areasIds = \App\Models\Area::pluck('id')->toArray();
        
        if (empty($areasIds)) {
            $areasIds = range(1, 10); // IDs 1-10 según AreaSeeder
        }

        // Nombres de calles reales de Puno
        $callesPuno = [
            "Av. El Sol", "Av. La Torre", "Av. Los Incas", "Av. Titicaca",
            "Av. Floral", "Av. Circunvalación", "Av. Simón Bolívar",
            "Av. El Maestro", "Av. Los Álamos", "Av. Tacna",
            "Av. Arequipa", "Av. Miraflores", "Av. Bellavista",
            "Jr. Lima", "Jr. Ayacucho", "Jr. Deustua", "Jr. Independencia",
            "Jr. Grau", "Jr. Puno", "Jr. Oquendo", "Jr. 2 de Febrero",
            "Calle Cajamarca", "Calle Ancash", "Calle Huancavelica",
            "Calle Moquegua", "Calle Lambayeque", "Calle Piura",
            "Psje. San Carlos", "Psje. Santa Rosa", "Psje. San Martín",
        ];

        // Tipos de edificios en Puno
        $tiposEdificio = [
            "Edificio", "Conjunto Habitacional", "Residencial", 
            "Condominio", "Casa", "Local"
        ];

        // Nombres de edificios/residenciales
        $nombresEdificios = [
            "El Sol", "Titicaca", "Los Andes", "Altipiano", "Puno Real",
            "Las Torres", "Mirador del Lago", "Bellavista", "Miraflores",
            "San Carlos", "Santa María", "San Martín"
        ];

        // Tipos de vivienda
        $tiposVivienda = ["Departamento", "Casa", "Dúplex", "Penthouse"];

        $calle = $this->faker->randomElement($callesPuno);
        $tipoEdificio = $this->faker->randomElement($tiposEdificio);
        $nombreEdificio = $this->faker->randomElement($nombresEdificios);
        $tipoVivienda = $this->faker->randomElement($tiposVivienda);

        // Números realistas
        $numeroEdificio = $this->faker->numberBetween(100, 2500);
        $piso = $this->faker->optional(0.7, 0)->numberBetween(0, 8);
        
        // Generar número de vivienda según tipo
        if ($tipoVivienda === "Casa") {
            $sector = $this->faker->randomElement(['A', 'B', 'C', 'D']);
            $numeroVivienda = "Mz. {$sector} Lt. " . $this->faker->numberBetween(1, 30);
        } elseif ($tipoVivienda === "Departamento") {
            $numeroVivienda = "Dpto. " . $this->faker->numberBetween(101, 808);
        } elseif ($tipoVivienda === "Dúplex") {
            $numeroVivienda = "Dúplex " . $this->faker->randomElement(['A', 'B', 'C']);
        } else {
            $numeroVivienda = $tipoVivienda . " " . $this->faker->numberBetween(1, 10);
        }

        // Determinar si es dirección principal (60% probabilidad)
        $esPrincipal = $this->faker->boolean(60) ? 1 : 0;

        return [
            "street_name" => $calle,
            "building_number" => "{$tipoEdificio} {$nombreEdificio} #{$numeroEdificio}",
            "floor_number" => $piso,
            "flat_number" => $numeroVivienda,
            "is_main" => $esPrincipal,
            "area_id" => $this->faker->randomElement($areasIds), // Área existente
            "user_id" => $this->faker->randomElement($usuariosIds), // Usuario existente
        ];
    }
}