<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar el ID de Perú en la tabla countries
        $peru = DB::table('countries')->where('name', 'Peru')->first();
        
        if (!$peru) {
            // Si no encuentra "Peru", buscar "Perú" con acento
            $peru = DB::table('countries')->where('name', 'LIKE', '%Perú%')->first();
        }
        
        if ($peru) {
            Area::factory(10)->create([
                "country_id" => $peru->id
            ]);
            
            echo "Áreas creadas para: " . $peru->name . " (ID: " . $peru->id . ")\n";
        } else {
            echo "ERROR: No se encontró Perú en la tabla countries\n";
            echo "Usando ID 604 por defecto...\n";
            
            Area::factory(10)->create([
                "country_id" => 604
            ]);
        }
    }
}