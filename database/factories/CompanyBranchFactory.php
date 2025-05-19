<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyBranch>
 */
class CompanyBranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() 
    {
        return [
            'company_id' => Company::factory(),
            'name_en' => $this->faker->city,
            'name_ar' => $this->faker->city,
        ];
    }
}
