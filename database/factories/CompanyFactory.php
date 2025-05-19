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
            'name_en' => $this->faker->company,
            'name_ar' => $this->faker->company,
            'logo' => null,
            'website' => $this->faker->url,
            'status' => 'active',
        ];
    }
}
