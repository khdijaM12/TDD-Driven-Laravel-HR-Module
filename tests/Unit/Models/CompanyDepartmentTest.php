<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use App\Models\CompanyDepartment; 

class CompanyDepartmentTest extends TestCase
{
    use RefreshDatabase;

     public function test_fillable_properties()
    {
        $department = new CompanyDepartment();

        $expected = ['company_id', 'name_en', 'name_ar'];

        $this->assertEquals($expected, $department->getFillable());
    }

    public function test_company_relationship()
    {
        $department = new CompanyDepartment();
        $this->assertInstanceOf(BelongsTo::class, $department->company());
    }
}
