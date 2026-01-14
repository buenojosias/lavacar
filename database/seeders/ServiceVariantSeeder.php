<?php

namespace Database\Seeders;

use App\Enums\VehicleSizeEnum;
use App\Models\ServiceType;
use App\Models\ServiceVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceVariantSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = VehicleSizeEnum::cases();
        $services = ServiceType::all();
        $durations = [30, 60, 90, 120];

        foreach ($services as $service) {
            $duration = 30;
            $price = rand(4, 8);
            foreach ($sizes as $size) {
                ServiceVariant::create([
                    'service_type_id' => $service->id,
                    'vehicle_size' => $size->value,
                    'duration' => $duration,
                    'price' => $price * 1000,
                    'is_active' => true,
                ]);
                $duration = $duration + fake()->randomElement([0, 30]);
                $price = $price + rand(0, 6);
            }
        }
    }
}
