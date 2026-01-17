<?php

namespace Database\Seeders;

use App\Enums\CompanyRoleEnum;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
                $users = User::where('role', 'PARTNER')->get();

        $companies = [
            [
                'city_id' => 4106902,
                'name' => 'Lava-Car Santa Bárbara',
                'cnpj' => '12345678901234',
                'address' => 'R. Amauri Lange Silvério, 730',
                'zipcode' => '82120-000',
                'district' => 'Pilarzinho',
                'latitude' => -25.3893991,
                'longitude' => -49.2942842,
                'whatsapp' => '+55419'.rand(82000000, 99999999),
                'simultaneous_services' => 1,
                'rating_avg' => 4.5,
                'rating_count' => 1,
            ],
            [
                'city_id' => 4106902,
                'name' => 'Rick Lava Car',
                'cnpj' => '12345678901235',
                'address' => 'Rua João Tschannerl, 642',
                'zipcode' => '80820-010',
                'district' => 'Vista Alegre',
                'latitude' => -25.3893991,
                'longitude' => -49.2942842,
                'whatsapp' => '+55419'.rand(82000000, 99999999),
                'simultaneous_services' => 2,
                'rating_avg' => 4.9,
                'rating_count' => 5,
            ],
            [
                'city_id' => 4100400,
                'name' => 'Lava Car Brasa',
                'cnpj' => '12345678901237',
                'address' => 'R. São Jorge, 61',
                'zipcode' => '83501-380',
                'district' => 'Parque Sao Jorge',
                'latitude' => -25.329882,
                'longitude' => -49.2837426,
                'whatsapp' => '+55419'.rand(82000000, 99999999),
                'simultaneous_services' => 3,
                'rating_avg' => 3.7,
                'rating_count' => 9,
            ],
            [
                'city_id' => 4105805,
                'name' => 'Lava Car Godoy',
                'cnpj' => '12345678201237',
                'address' => 'Rod. da Uva - Arruda, Colombo - PR',
                'zipcode' => '83401-520',
                'district' => 'Arruda',
                'latitude' => -25.321102,
                'longitude' => -49.2484919,
                'whatsapp' => '+55419'.rand(82000000, 99999999),
                'simultaneous_services' => rand(1, 4),
                'rating_avg' => 4.5,
                'rating_count' => 2,
            ],
        ];

        foreach ($companies as $company) {
            $company['is_visible'] = rand(0, 1);
            $company = Company::create($company);
            $user = $users->random();
            $company->users()->attach($user->id, ['role' => CompanyRoleEnum::OWNER->value]);
            $user->update(['selected_company_id' => $company->id, 'selected_company_role' => 'OWNER']);
        }
    }
}
