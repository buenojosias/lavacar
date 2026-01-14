<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = collect([
            [
                'name' => 'Ducha simples',
                'description' => 'Limpeza externa com água, shampoo e panos de microfibra, mais aspiração interna básica e limpeza de vidros.',
                'is_active' => true,
            ],
            [
                'name' => 'Lavagem Completa',
                'description' => 'Inclui a simples, mas com mais atenção aos detalhes internos e externos, como limpeza de rodas e caixa de roda.',
                'is_active' => true,
            ],
            [
                'name' => 'Lavagem a Seco',
                'description' => 'Uso de produtos biodegradáveis e pouca água, ideal para carros pouco sujos ou para manutenção sustentável.',
                'is_active' => true,
            ],
            [
                'name' => 'Higienização Interna',
                'description' => 'Limpeza profunda de bancos, teto, carpetes e porta-malas, podendo incluir higienização do ar-condicionado.',
                'is_active' => true,
            ],
            [
                'name' => 'Lavagem com Snow Foam',
                'description' => 'Aplicação de espuma densa para uma limpeza profunda e segura da pintura.',
                'is_active' => true,
            ],
        ]);

        $companies = Company::all();

        foreach ($companies as $company) {
            $company->serviceTypes()->createMany($types->random(rand(2, 4))->toArray());
        }
    }
}
