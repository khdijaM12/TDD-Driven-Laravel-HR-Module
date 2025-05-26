<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('company holidays table has expected columns', function () {
    expect(Schema::hasTable('company_holidays'))->toBeTrue();

    foreach(['id', 'occasion', 'date_from', 'date_to', 'company_id', 'branch_id', 'created_at', 'updated_at'] as $column){
        expect(Schema::hasColumn('company_holidays', $column))->toBeTrue("Missing column: $column");
    }
});

test('company holidays table has foreign keys', function () {
    $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_holidays' AND COLUMN_NAME IN ('company_id', 'branch_id')");

    expect($foreignKeys)->not->toBeEmpty("company_id or branch_id should reference parent tables");
});