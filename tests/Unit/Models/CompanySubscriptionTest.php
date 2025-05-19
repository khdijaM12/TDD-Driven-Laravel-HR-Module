<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use App\Models\CompanySubscription; 
use App\Models\Company;

class CompanySubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_properties()
    {
        $subscription = new CompanySubscription();

        $expected = ['company_id', 'subscribe_start', 'subscribe_end', 'number_employees'];

        $this->assertEquals($expected, $subscription->getFillable());
    }

    public function test_company_relationship()
    {
        $subscription = new CompanySubscription();
        $this->assertInstanceOf(BelongsTo::class, $subscription->company());
    }

    public function test_subscription_end_date_must_be_after_start_date()
    {
        $this->expectException(ValidationException::class);

        $company = Company::factory()->create();
        
        CompanySubscription::create([
            'company_id' => $company->id,
            'subscribe_start' => '2025-01-10',
            'subscribe_end' => '2025-01-01',
            'number_employees' => 10
        ]);
    }
}