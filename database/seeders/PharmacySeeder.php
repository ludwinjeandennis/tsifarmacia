<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Obtener el usuario pharmacy (ya creado en UserSeeder)
        $pharmacyOwner = User::where('email', 'pharmacy@test.com')->first();
        
        if (!$pharmacyOwner) {
            // Si por alguna razón no existe, crearlo
            $pharmacyOwner = User::create([
                'name' => 'Dueño Farmacia',
                'password' => '123456',
                'national_id' => '22222000022223',
                'email' => 'pharmacy@test.com',
                'gender' => '1',
                'phone' => '01066362246',
                'date_of_birth' => fake()->date(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ]);
            
            $pharmacyOwner->assignRole("pharmacy");
        }

        // 2. Crear la farmacia principal
        $pharmacy = Pharmacy::create([
            'name' => 'Farmacia Principal Puno',
            'area_id' => 1, // Área ID 1 (creada por AreaSeeder)
            'owner_id' => $pharmacyOwner->id, // ID 2
            'priority' => 1,
        ]);

        // 3. Actualizar el usuario pharmacy con el ID de la farmacia
        $pharmacyOwner->update([
            "pharmacy_id" => $pharmacy->id
        ]);

        // 4. Obtener el doctor existente (creado en UserSeeder) y asociarlo a la farmacia
        $doctor = User::where('email', 'doctor_1@test.com')->first();
        
        if ($doctor) {
            // Actualizar el doctor con el pharmacy_id
            $doctor->update([
                'pharmacy_id' => $pharmacy->id
            ]);
            
            // Asegurar que tenga rol doctor (por si acaso)
            if (!$doctor->hasRole('doctor')) {
                $doctor->assignRole("doctor");
            }
            
            echo "Doctor asociado a la farmacia: " . $doctor->name . "\n";
        } else {
            echo "Advertencia: No se encontró el doctor creado en UserSeeder\n";
        }

        echo "Farmacia creada: " . $pharmacy->name . " (ID: " . $pharmacy->id . ")\n";
        echo "Dueño: " . $pharmacyOwner->name . " (ID: " . $pharmacyOwner->id . ")\n";
    }
}