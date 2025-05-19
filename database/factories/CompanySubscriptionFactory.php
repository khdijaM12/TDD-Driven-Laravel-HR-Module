<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanySubscription>
 */
class CompanySubscriptionFactory extends Factory
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
            'subscribe_start' => $this->faker->date(),
            'subscribe_end' => $this->faker->date(),
            'number_employees' => $this->faker->numberBetween(1, 100),
        ];
    }
}
