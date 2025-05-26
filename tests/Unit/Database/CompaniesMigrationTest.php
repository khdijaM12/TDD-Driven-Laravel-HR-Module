<?php

use Illuminate\Support\Facades\Schema;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('companies table has expected columns', function () {
    expect(Schema::hasTable('companies'))->toBeTrue();

    foreach (['id', 'name_en', 'name_ar', 'logo', 'website', 'status', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('companies', $column))->toBeTrue("Missing column: $column");
    }
});