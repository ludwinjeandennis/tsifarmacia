<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class UpdateMedicineStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = Medicine::all();
        
        echo "Actualizando stock de {$medicines->count()} medicamentos...\n";
        
        foreach ($medicines as $medicine) {
            $medicine->update([
                'stock' => rand(10, 100),
                'min_stock' => rand(5, 20)
            ]);
        }
        
        echo "✓ Stock actualizado exitosamente!\n";
    }
}
