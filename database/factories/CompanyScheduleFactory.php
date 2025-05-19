<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanySchedule>
 */
class CompanyScheduleFactory extends Factory
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
            'slug' => $this->faker->unique()->slug,
            'weekend_days' => json_encode(['friday']),
            'check_in_time' => '08:00:00',
            'check_out_time' => '17:00:00',
        ];
    }
}
