<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyJob;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyJobFactory extends Factory
{
    protected $model = CompanyJob::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name_en' => $this->faker->jobTitle,
            'name_ar' => $this->faker->jobTitle,
        ];
    }
}