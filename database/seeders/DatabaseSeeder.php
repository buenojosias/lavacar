<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'whatsapp' => null,
            'role' => 'ADMIN',
            'password' => '123456',
        ]);

        User::factory(10)->create([
            'role' => 'CUSTOMER',
        ]);
        User::factory(10)->create(['role' => 'PARTNER', 'whatsapp' => null]);

        $this->call([
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
