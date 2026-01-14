<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyVehicleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::whereNull('user_id')->get();

        $models = collect([
            ['brand_model' => 'Fiat Mobi', 'size' => 'SM'],
            ['brand_model' => 'Hyundai HB20', 'size' => 'SM'],
            ['brand_model' => 'Chevrolet Onix', 'size' => 'MD'],
            ['brand_model' => 'Volkswagen Voyage', 'size' => 'MD'],
            ['brand_model' => 'Fiat Pulse', 'size' => 'LG'],
            ['brand_model' => 'Volkswagen T-Cross', 'size' => 'LG'],
            ['brand_model' => 'Volkswagen Amarok', 'size' => 'XL'],
            ['brand_model' => 'Mitsubishi L200', 'size' => 'XL'],
            ['brand_model' => 'Honda CG 160', 'size' => 'MC'],
            ['brand_model' => 'Yamaha Neo 125', 'size' => 'MC'],
        ]);

        $colors = [
            'Azul',
            'Preto',
            'Branco',
            'Vermelho',
            'Cinza',
        ];

        foreach ($customers as $customer) {
            $vehicleModel = $models->random(1)->first();
            $vehicle = Vehicle::create([
                'plate' => strtoupper(fake()->randomElement([fake()->bothify('???####'), fake()->bothify('???#?##')])),
                'brand_model' => $vehicleModel['brand_model'],
                'size' => $vehicleModel['size'],
                'color' => $colors[array_rand($colors)],
            ]);

            $vehicle->companyVehicles()->create([
                'company_id' => $customer->company_id,
                'customer_id' => $customer->id,
                'nickname' => $vehicleModel['brand_model'].' '.$vehicleModel['size'].' ('.$vehicle->plate.')',
            ]);
        }
    }
}
