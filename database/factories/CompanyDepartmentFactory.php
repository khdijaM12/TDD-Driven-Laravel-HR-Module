<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyDepartmentFactory extends Factory
{
    protected $model = CompanyDepartment::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name_en' => $this->faker->word,
            'name_ar' => $this->faker->word,
        ];
    }
}
