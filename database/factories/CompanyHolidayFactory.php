<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\CompanyBranch;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyHoliday>
 */
class CompanyHolidayFactory extends Factory
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
            'branch_id' => CompanyBranch::factory(),
            'occasion' => $this->faker->word,
            'date_from' => $this->faker->date(),
            'date_to' => $this->faker->date(),
        ];
    }
}
