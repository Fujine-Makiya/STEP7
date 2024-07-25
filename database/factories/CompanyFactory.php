<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class CompanyFactory extends Factory
{
   
    public function definition(): array
    {
        static $companies = [
            'サントリー',
            'アサヒ',
            'キリン',
            'コカコーラ',
            '伊藤園'
        ];

        return [
            'company_name' => $this->faker->randomElement($companies),
            'street_address' => $this->faker->streetAddress,
            'representative_name' => $this->faker->name,
        ];
    }
}


