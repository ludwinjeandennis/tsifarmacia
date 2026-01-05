<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PurchaseOrderSeeder extends Seeder
{
    public function run()
    {
        // Primero asegurar que existen proveedores
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

        echo "Creando proveedores...\n";
        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
        
        $allSuppliers = Supplier::all();
        echo "✓ {$allSuppliers->count()} proveedores creados/actualizados\n";

        // Crear órdenes de compra de ejemplo
        echo "Creando órdenes de compra de ejemplo...\n";
        
        $statuses = ['Pending', 'Approved', 'Received', 'Cancelled'];
        
        for ($i = 0; $i < 20; $i++) {
            $supplier = $allSuppliers->random();
            $status = $statuses[array_rand($statuses)];
            
            PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'status' => $status,
                'total_amount' => rand(500, 5000),
                'expected_delivery_date' => Carbon::now()->addDays(rand(1, 30)),
                'notes' => 'Orden de compra generada automáticamente - ' . $supplier->name,
                'created_at' => Carbon::now()->subDays(rand(0, 60))
            ]);
        }
        
        echo "✓ 20 órdenes de compra creadas exitosamente!\n";
    }
}
