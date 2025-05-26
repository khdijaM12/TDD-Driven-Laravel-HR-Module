<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CompanyBranch;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('fillable properties', function () {
    $branch = new CompanyBranch();

    $expected = ['company_id', 'name_en', 'name_ar'];

    expect($branch->getFillable())->toEqual($expected);
});

test('company relationship', function () {
    $branch = new CompanyBranch();
    expect($branch->company())->toBeInstanceOf(BelongsTo::class);
});

test('holidays relationship', function () {
    $branch = new CompanyBranch();
    expect($branch->holidays())->toBeInstanceOf(HasMany::class);
});