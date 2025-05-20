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
        $start = $this->faker->dateTimeBetween('-1 month', 'now');
        $end = (clone $start)->modify('+1 month');

        return [
            'company_id' => Company::factory(),
            'subscribe_start' => $start,
            'subscribe_end' => $end,
            'number_employees' => $this->faker->numberBetween(1, 100),
        ];
    }

}
