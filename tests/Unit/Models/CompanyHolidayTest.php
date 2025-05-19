<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use App\Models\CompanyHoliday; 
use App\Models\Company;
use App\Models\CompanyBranch;

class CompanyHolidayTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_properties()
    {
        $holiday = new CompanyHoliday();

        $expected = ['company_id', 'occasion', 'date_from', 'date_to', 'branch_id'];

        $this->assertEquals($expected, $holiday->getFillable());
    }

        public function test_company_relationship()
    {
        $holiday = new CompanyHoliday();
        $this->assertInstanceOf(BelongsTo::class, $holiday->company());
    }

    public function test_branch_relationship()
    {
        $holiday = new CompanyHoliday();
        $this->assertInstanceOf(BelongsTo::class, $holiday->branch());
    }

    public function test_holiday_end_date_must_be_after_or_equal_to_start_date()
    {
        $this->expectException(ValidationException::class);

        $company = Company::factory()->create();
        
        CompanyHoliday::create([
            'company_id' => $company->id,
            'occasion' => 'Test Holiday',
            'date_from' => '2023-01-10',
            'date_to' => '2023-01-01'
        ]);
    }

    public function test_valid_holiday_dates()
    {
        $company = Company::factory()->create();
        
        $holiday = CompanyHoliday::create([
            'company_id' => $company->id,
            'occasion' => 'Test Holiday',
            'date_from' => '2023-01-01',
            'date_to' => '2023-01-10'
        ]);

        $this->assertDatabaseHas('company_holidays', [
            'id' => $holiday->id,
            'occasion' => 'Test Holiday'
        ]);
    }
}