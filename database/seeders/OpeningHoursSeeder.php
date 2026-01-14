<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class OpeningHoursSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $openM = Arr::random(['08:00:00', '08:30:00', '09:00:00', '09:30:00']);
            $closeM = Arr::random(['11:00:00', '11:30:00', '12:00:00', '12:30:00']);
            $openT = Arr::random(['13:00:00', '13:30:00', '14:00:00', '14:30:00']);
            $closeT = Arr::random(['17:00:00', '17:30:00', '18:00:00', '18:30:00', '19:00:00']);

            for ($i = 1; $i < 6; $i++) {
                $company->hours()->create([
                    'weekday' => $i,
                    'opens_at' => $openM,
                    'closes_at' => $closeM,
                ]);
            }
            for ($i = 1; $i < 6; $i++) {
                $company->hours()->create([
                    'weekday' => $i,
                    'opens_at' => $openT,
                    'closes_at' => $closeT,
                ]);
            }
            $company->hours()->create([
                'weekday' => 6,
                'opens_at' => $openM,
                'closes_at' => Arr::random(['12:30:00', '13:00:00', '13:30:00', '14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00', '16:30:00']),
            ]);
        }
    }
}
