<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            [
                'id' => 4106902,
                'name' => 'Curitiba',
                'state' => 'PR',
            ],
            [
                'id' => 4100400,
                'name' => 'Almirante Tamandaré',
                'state' => 'PR',
            ],
            [
                'id' => 4105805,
                'name' => 'Colombo',
                'state' => 'PR',
            ],
            [
                'id' => 4119152,
                'name' => 'Pinhais',
                'state' => 'PR',
            ]
        ];

        City::insert($cities);
    }
}
