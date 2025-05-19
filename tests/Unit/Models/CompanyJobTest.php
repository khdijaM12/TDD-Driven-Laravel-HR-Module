<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use App\Models\CompanyJob;
use App\Models\Company;

class CompanyJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_properties()
    {
        $job = new CompanyJob();

        $expected = ['company_id', 'name_en', 'name_ar'];

        $this->assertEquals($expected, $job->getFillable());
    }

    public function test_company_relationship()
    {
        $job = new CompanyJob();
        $this->assertInstanceOf(BelongsTo::class, $job->company());
    }
}
