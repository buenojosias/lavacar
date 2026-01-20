<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'whatsapp' => null,
            'role' => 'ADMIN',
            'password' => '123456',
        ]);

        User::factory()->create([
            'name' => 'Josias Bueno',
            'email' => 'josias@example.com',
            'whatsapp' => null,
            'role' => 'PARTNER',
            'password' => '123456',
        ]);

        User::factory()->create([
            'name' => 'Marcio Chiareli',
            'email' => 'marcio@example.com',
            'whatsapp' => null,
            'role' => 'PARTNER',
            'password' => '123456',
        ]);
        
        User::factory(10)->create(['role' => 'PARTNER', 'whatsapp' => null]);
        
        User::factory(10)->create([
            'role' => 'CUSTOMER',
        ]);
    }
}
