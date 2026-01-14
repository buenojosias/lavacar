<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'cnpj' => fake()->cnpj(),
            'address' => fake()->address(),
            'zipcode' => fake()->postcode(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'rating' => fake()->randomFloat(1, 1, 5),
        ];
    }
}
