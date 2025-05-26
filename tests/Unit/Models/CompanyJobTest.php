<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CompanyJob;
use App\Models\Company;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $job = new CompanyJob();

    $expected = ['company_id', 'name_en', 'name_ar'];

    expect($job->getFillable())->toEqual($expected);
});

test('company relationship', function () {
    $job = new CompanyJob();
    expect($job->company())->toBeInstanceOf(BelongsTo::class);
});