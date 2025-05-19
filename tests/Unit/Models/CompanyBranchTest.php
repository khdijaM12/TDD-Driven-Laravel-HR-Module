<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;
use App\Models\CompanyBranch; 

class CompanyBranchTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_properties()
    {
        $branch = new CompanyBranch();

        $expected = ['company_id', 'name_en', 'name_ar'];

        $this->assertEquals($expected, $branch->getFillable());
    }

        public function test_company_relationship()
    {
        $branch = new CompanyBranch();
        $this->assertInstanceOf(BelongsTo::class, $branch->company());
    }

    public function test_holidays_relationship()
    {
        $branch = new CompanyBranch();
        $this->assertInstanceOf(HasMany::class, $branch->holidays());
    }
}
