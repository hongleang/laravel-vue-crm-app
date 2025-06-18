<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $industries = [
            'Information Technology',
            'Software Development',
            'Aerospace',
            'Consulting',
            'Financial Services',
            'Biotechnology',
            'Renewable Energy',
            'Manufacturing',
            'Healthcare',
            'Logistics'
        ];

        return [
            'name' => fake()->company,
            'industry' => fake()->randomElement($industries),
            'phone' => '04' . fake()->numerify('########'),
            'email' => fake()->unique()->email(),
            'address' => fake()->address(),
            'creator_id' => User::factory(),
        ];
    }
}
