<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use App\Models\Company;
use App\Models\CompanyJob;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_properties()
    {
        $company = new Company();

        $expected = ['name_en', 'name_ar', 'logo', 'website', 'status'];

        $this->assertEquals($expected, $company->getFillable());
    }

    public function test_company_has_many_jobs_relationship()
    {
        $company = new Company();

        $this->assertInstanceOf(HasMany::class, $company->jobs());
    }
}
