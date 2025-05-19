<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use App\Models\CompanySchedule; 
use App\Models\Company;

class CompanyScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_properties()
    {
        $schedule = new CompanySchedule();

        $expected = ['company_id', 'slug', 'weekend_days', 'check_in_time', 'check_out_time'];

        $this->assertEquals($expected, $schedule->getFillable());
    }

    public function test_company_relationship()
    {
        $schedule = new CompanySchedule();
        $this->assertInstanceOf(BelongsTo::class, $schedule->company());
    }

     public function test_slug_must_be_unique()
    {
        $this->expectException(ValidationException::class);

        $company = Company::factory()->create();

        CompanySchedule::factory()->create([
            'company_id' => $company->id,
            'slug' => 'test-schedule',
        ]);

        CompanySchedule::factory()->create([
            'company_id' => $company->id,
            'slug' => 'test-schedule',
        ]);
    }
}