<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lista de medicamentos comunes en Perú
        $medicamentosPeruanos = [
            "Paracetamol 500mg",
            "Ibuprofeno 400mg",
            "Amoxicilina 500mg",
            "Omeprazol 20mg",
            "Loratadina 10mg",
            "Metformina 850mg",
            "Losartán 50mg",
            "Atorvastatina 20mg",
            "Salbutamol Inhalador",
            "Diclofenaco 50mg",
            "Azitromicina 500mg",
            "Ciprofloxacino 500mg",
            "Clonazepam 2mg",
            "Simvastatina 20mg",
            "Enalapril 10mg",
            "Acetaminofén 500mg",
            "Naproxeno 500mg",
            "Metronidazol 500mg",
            "Ranitidina 150mg",
            "Cetirizina 10mg",
            "Dipirona 500mg",
            "Furosemida 40mg",
            "Hidroclorotiazida 25mg",
            "Levotiroxina 50mcg",
            "Insulina NPH",
            "Ácido acetilsalicílico 100mg",
            "Ambroxol 30mg",
            "Bromhexina 8mg",
            "Ketorolaco 10mg",
            "Prednisona 5mg",
            "Cefalexina 500mg",
            "Dexametasona 4mg",
            "Fluconazol 150mg",
            "Gabapentina 300mg",
            "Heparina 5000UI",
            "Miconazol crema",
            "Nistatina crema",
            "Povidona yodada",
            "Sales de rehidratación oral",
            "Suero fisiológico"
        ];

        // Tipos de medicamentos en español
        $medicineTypes = [
            "Tableta", 
            "Cápsula", 
            "Inyección", 
            "Jarabe", 
            "Crema", 
            "Ungüento",
            "Inhalador",
            "Supositorio",
            "Polvo",
            "Suspensión",
            "Spray",
            "Gotas",
            "Parche",
            "Óvulo"
        ];

        // Precios realistas en soles peruanos (S/)
        $precios = [
            2.50, 3.00, 3.50, 4.00, 4.50, 5.00, 5.50, 6.00, 6.50, 7.00,
            7.50, 8.00, 8.50, 9.00, 9.50, 10.00, 12.00, 15.00, 18.00,
            20.00, 25.00, 30.00, 35.00, 40.00, 45.00, 50.00, 60.00, 75.00
        ];

        return [
            'name' => $medicamentosPeruanos[array_rand($medicamentosPeruanos)],
            'type' => $medicineTypes[array_rand($medicineTypes)],
            'price' => $precios[array_rand($precios)],
        ];
    }
}