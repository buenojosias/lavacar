<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::with('users')->get();
        $users = User::where('role', 'CUSTOMER')->get();

        // Creating from users
        $users->each(function ($user) use ($companies) {
            $avaliableCompanies = $companies;
            for ($i = 1; $i <= rand(1, 3); $i++) {
                $company_id = $avaliableCompanies->random()->id;
                Customer::factory()->create([
                    'user_id' => $user->id,
                    'company_id' => $company_id,
                    'name' => $user->name,
                    'whatsapp' => $user->whatsapp,
                ]);
                $avaliableCompanies = $avaliableCompanies->where('id', '!=', $company_id);
            }
        });

        // Creating from companies
        $companies->each(function ($company) {
            $registrar_id = $company->users->random()->id;
            Customer::factory(2)->create([
                'company_id' => $company->id,
                'registered_by_user_id' => $registrar_id,
            ]);
        });
    }
}
