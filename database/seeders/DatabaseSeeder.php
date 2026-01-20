<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CitySeeder::class,
            CompanySeeder::class,
            OpeningHoursSeeder::class,
            CustomerSeeder::class,
            ServiceTypeSeeder::class,
            ServiceVariantSeeder::class,
            VehicleSeeder::class,
            CompanyVehicleSeeder::class,
        ]);
    }
}
