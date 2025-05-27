<?php

namespace Database\Factories;

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
    public function definition()
{
    return [
         'name' => [
            'en' => $this->faker->unique()->company,
            'ar' => 'شركة ' . $this->faker->unique()->company,
        ],
        'logo' => null,
        'website' => $this->faker->url,
        'status' => $this->faker->randomElement(['active', 'inactive']),
    ];
}
}
