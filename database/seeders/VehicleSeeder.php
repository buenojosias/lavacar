<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $models = collect([
            ['brand_model' => 'Fiat Mobi', 'year' => 2020, 'category' => 'Hatch', 'size' => 'SM'],
            ['brand_model' => 'Hyundai HB20', 'year' => 2019, 'category' => 'Hatch', 'size' => 'SM'],
            ['brand_model' => 'Chevrolet Onix', 'year' => 2021, 'category' => 'Sedan', 'size' => 'MD'],
            ['brand_model' => 'Volkswagen Voyage', 'year' => 2018, 'category' => 'Sedan', 'size' => 'MD'],
            ['brand_model' => 'Fiat Pulse', 'year' => 2022, 'category' => 'SUV', 'size' => 'LG'],
            ['brand_model' => 'Volkswagen T-Cross', 'year' => 2023, 'category' => 'SUV', 'size' => 'LG'],
            ['brand_model' => 'Volkswagen Amarok', 'year' => 2020, 'category' => 'Pickup', 'size' => 'XL'],
            ['brand_model' => 'Mitsubishi L200', 'year' => 2019, 'category' => 'Pickup', 'size' => 'XL'],
            ['brand_model' => 'Honda CG 160', 'year' => 2021, 'category' => 'Motocicleta', 'size' => 'MC'],
            ['brand_model' => 'Yamaha Neo 125', 'year' => 2022, 'category' => 'Motocicleta', 'size' => 'MC'],
        ]);

        $colors = [
            'Azul',
            'Preto',
            'Branco',
            'Vermelho',
            'Cinza',
        ];

        $users = User::where('role', 'CUSTOMER')->with('customers.company', function($query) {
            $query->withoutGlobalScopes();
        })->get();

        foreach ($users as $user) {
            foreach ($user->customers as $customer) {
                $vehicleModel = $models->random(1)->first();
                $vehicle = Vehicle::withoutGlobalScopes()->create([
                    'user_id' => $user->id,
                    'plate' => strtoupper(fake()->randomElement([fake()->bothify('???####'), fake()->bothify('???#?##')])),
                    'brand_model' => $vehicleModel['brand_model'],
                    'category' => $vehicleModel['category'],
                    'year' => $vehicleModel['year'],
                    'size' => $vehicleModel['size'],
                    'color' => $colors[array_rand($colors)],
                ]);

                $vehicle->companyVehicles()->withoutGlobalScopes()->create([
                    'company_id' => $customer->company_id,
                    'customer_id' => $customer->id,
                    'nickname' => $vehicleModel['brand_model'].' '.$vehicleModel['year'].' ('.$vehicle->plate.')',
                ]);
            }
        }
    }
}
