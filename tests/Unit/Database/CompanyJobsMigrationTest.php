<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('company jobs table has expected columns', function () {
    expect(Schema::hasTable('company_jobs'))->toBeTrue();

    foreach (['id', 'company_id', 'name_en', 'name_ar', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('company_jobs', $column))->toBeTrue("Missing column: $column");
    }
});

test('company jobs table has foreign key', function () {
    $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_jobs' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

    expect($foreignKeys)->not->toBeEmpty("company_id should reference companies.id");
});