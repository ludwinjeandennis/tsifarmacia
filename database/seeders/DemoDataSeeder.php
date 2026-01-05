<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\User;
use App\Models\Pharmacy;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure we have enough medicines
        if (Medicine::count() < 100) {
            Medicine::factory(500)->create();
        }
        
        // Reload all medicines to include existing ones
        $allMedicines = Medicine::all();
        
        // Update all medicines with stock data
        $allMedicines->each(function($medicine) {
            $medicine->update([
                'stock' => rand(10, 100),
                'min_stock' => rand(5, 20)
            ]);
        });

        // 2. Ensure we have a pharmacy to attach orders to
        $pharmacy = Pharmacy::first();
        if (!$pharmacy) {
            $pharmacy = Pharmacy::factory()->create();
        }

        // 3. Ensure we have clients
        $clients = User::role('client')->get();
        if ($clients->isEmpty()) {
            // Fallback if no clients exist
            $clients = User::factory(50)->create();
             foreach ($clients as $client) {
                 $client->assignRole('client');
             }
        }
        
        $doctor = User::role('doctor')->first();
        $doctorId = $doctor ? $doctor->id : null;


        // 4. Generate Orders for the current year (Jan to present)
        $currentYear = Carbon::now()->year;
        $months = range(1, 12);

        foreach ($months as $month) {
            // Create 600-900 orders per month
            $ordersCount = rand(600, 900);
            
            $this->command->info("Generating {$ordersCount} orders for month {$month}...");

            $ordersBatch = [];
            
            for ($i = 0; $i < $ordersCount; $i++) {
                // Random date within the month
                // Handle Feb days (simplification)
                $maxDays = ($month == 2) ? 28 : 30; 
                $date = Carbon::create($currentYear, $month, rand(1, $maxDays), rand(9, 18), rand(0, 59));
                
                // Skip if future date
                if ($date->isFuture()) continue;

                // 1:New, 2:Processing, 3:Waiting, 4:Cancelled, 5:Confirmed, 6:Delivered
                // Favor "Delivered" (6) and "Confirmed" (5)
                $status = rand(1, 10) > 3 ? (rand(0, 1) ? 5 : 6) : rand(1, 4);

                $order = Order::create([
                    'status' => $status,
                    'is_insured' => rand(0, 1),
                    'pharmacy_id' => $pharmacy->id,
                    'user_id' => $clients->random()->id,
                    'doctor_id' => $doctorId ?? $clients->random()->id, // Fallback
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Attach medicines (1 to 5 types per order)
                if ($allMedicines->isNotEmpty()) {
                    $orderMedicines = $allMedicines->random(rand(1, 5));
                    
                    $pivotData = [];
                    foreach ($orderMedicines as $med) {
                        $pivotData[] = [
                            'order_id' => $order->id,
                            'medicine_id' => $med->id,
                            'quantity' => rand(1, 5),
                            'created_at' => $date,
                            'updated_at' => $date,
                        ];
                    }
                    DB::table('medicines_orders')->insert($pivotData);
                }
            }
        }
    }
}
