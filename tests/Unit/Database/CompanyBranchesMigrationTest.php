<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('company branches table has expected columns', function () {
    expect(Schema::hasTable('company_branches'))->toBeTrue();

    foreach (['id', 'company_id', 'name_en', 'name_ar', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('company_branches', $column))->toBeTrue("Missing column: $column");
    }
});

test('company branches table has foreign key', function () {
    $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_branches' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

    expect($foreignKeys)->not->toBeEmpty("company_id should reference companies.id");
});