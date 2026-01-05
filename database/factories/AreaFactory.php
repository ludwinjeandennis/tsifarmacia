<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Distritos y localidades de la región Puno
        $localidadesPuno = [
            // Provincia de Puno (Ciudad de Puno)
            "Puno (Centro)", "Puno (Alto Puno)", "Puno (Barrio Mañazo)", 
            "Puno (Barrio Chanu Chanu)", "Puno (Barrio Bellavista)",
            "Puno (Barrio Laicacota)", "Puno (Barrio Miraflores)",
            "Puno (Barrio Umacollo)", "Puno (Barrio Salcedo)",
            
            // Distritos de la provincia de Puno
            "Acora", "Amantani", "Atuncolla", "Capachica", "Chucuito",
            "Coata", "Huata", "Mañazo", "Paucarcolla", "Pichacani",
            "Plateria", "San Antonio", "Tiquillaca", "Vilque",
            
            // Otras provincias importantes de la región Puno
            "Juliaca", "Ilave", "Yunguyo", "Azángaro", "Huancané",
            "Lampa", "Melgar", "Mohoc", "Putina", "San Román",
            "Sandia", "Carabaya", "El Collao",
            
            // Localidades turísticas del Lago Titicaca
            "Islas Uros", "Isla Taquile", "Isla Amantani",
            "Isla Suasi", "Isla Anapia",
            
            // Comunidades y centros poblados
            "Chucuito (Pueblo)", "Juli (Distrito)", "Pomata",
            "Zepita", "Desaguadero", "Taraco", "Vilquechico"
        ];

        // Nombres de calles y avenidas típicas de Puno
        $callesPuno = [
            // Avenidas principales
            "Av. El Sol", "Av. La Torre", "Av. Los Incas", "Av. Titicaca",
            "Av. Floral", "Av. Circunvalación", "Av. Simón Bolívar",
            "Av. El Maestro", "Av. Los Álamos", "Av. Tacna",
            "Av. Arequipa", "Av. Miraflores", "Av. Bellavista",
            
            // Calles históricas
            "Jr. Lima", "Jr. Ayacucho", "Jr. Deustua", "Jr. Independencia",
            "Jr. Grau", "Jr. Puno", "Jr. Oquendo", "Jr. 2 de Febrero",
            "Jr. 7 de Junio", "Jr. Aroma", "Jr. Conde de Lemos",
            
            // Calles del centro
            "Calle Cajamarca", "Calle Ancash", "Calle Huancavelica",
            "Calle Moquegua", "Calle Lambayeque", "Calle Piura",
            "Calle Tarapacá", "Calle Libertad", "Calle San Román",
            
            // Pasajes y jirones
            "Psje. San Carlos", "Psje. Santa Rosa", "Psje. San Martín",
            "Psje. Bolognesi", "Psje. Los Pinos", "Psje. La Paz",
            
            // Carreteras y vías
            "Carretera Panamericana", "Vía Lago Titicaca", 
            "Carretera a Juliaca", "Vía a Chucuito", "Carretera a Ilave"
        ];

        // Referencias típicas de direcciones en Puno
        $referenciasPuno = [
            "frente al Parque Pino", "cerca de la Catedral", 
            "al costado del Colegio San Carlos", "a una cuadra del Lago Titicaca",
            "frente al Mercado Central", "cerca del Terminal Terrestre",
            "al lado del Hospital Regional", "frente a la Plaza de Armas",
            "cerca de la Universidad Nacional del Altiplano",
            "a dos cuadras del Estadio Enrique Torres Belón",
            "frente al Mirador del Lago", "cerca del Puerto de Puno",
            "al costado del Colegio Deustua", "frente al Parque Huajsapata",
            "cerca del Complejo Cultural"
        ];

        // Sectores o barrios para la dirección
        $sectores = [
            "Mz. A Lt. 1", "Mz. B Lt. 3", "Mz. C Lt. 5", "Mz. D Lt. 7",
            "Mz. E Lt. 9", "Mz. F Lt. 11", "Mz. G Lt. 13", "Mz. H Lt. 15",
            "Lote 2", "Lote 4", "Lote 6", "Lote 8", "Lote 10"
        ];

        $localidad = $localidadesPuno[array_rand($localidadesPuno)];
        $calle = $callesPuno[array_rand($callesPuno)];
        $numero = $this->faker->numberBetween(100, 2500);
        $sector = $sectores[array_rand($sectores)];
        $referencia = $referenciasPuno[array_rand($referenciasPuno)];

        return [
            'name' => $localidad,
            'address' => "{$calle} #{$numero}, {$sector} - {$localidad}. {$referencia}",
        ];
    }
}