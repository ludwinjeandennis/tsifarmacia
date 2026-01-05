<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'name' => 'Farmacéutica Global S.A.',
                'email' => 'ventas@farmaglobal.com',
                'phone' => '+1-555-0101',
                'address' => 'Av. Principal 123, Ciudad Capital'
            ],
            [
                'name' => 'Distribuidora MedPlus',
                'email' => 'contacto@medplus.com',
                'phone' => '+1-555-0202',
                'address' => 'Calle Comercio 456, Zona Industrial'
            ],
            [
                'name' => 'Laboratorios Salud Total',
                'email' => 'info@saludtotal.com',
                'phone' => '+1-555-0303',
                'address' => 'Parque Empresarial Norte, Lote 789'
            ],
            [
                'name' => 'Importadora Vita Med',
                'email' => 'pedidos@vitamed.com',
                'phone' => '+1-555-0404',
                'address' => 'Zona Franca, Bodega 12'
            ],
            [
                'name' => 'Suministros Farmacéuticos Express',
                'email' => 'express@sumifarma.com',
                'phone' => '+1-555-0505',
                'address' => 'Centro Logístico Sur, Nave 5'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']], // Unique identifier
                $supplier // Data to update/create
            );
        }
    }
}
