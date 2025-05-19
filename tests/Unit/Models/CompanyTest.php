<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Company;

class CompanyTest extends TestCase
{
    public function test_fillable_properties()
    {
        $company = new Company();

        $expected = ['name_en', 'name_ar', 'logo', 'website', 'status'];

        $this->assertEquals($expected, $company->getFillable());
    }
}
