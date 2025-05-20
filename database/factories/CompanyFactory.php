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
        'name_en' => $this->faker->unique()->company,
        'name_ar' => 'شركة اختبار',
        'logo' => null,
        'website' => $this->faker->url,
        'status' => $this->faker->randomElement(['active', 'inactive']),
    ];
}
}
