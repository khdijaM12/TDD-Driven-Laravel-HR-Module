<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('company schedules table has expected columns', function () {
    expect(Schema::hasTable('company_schedules'))->toBeTrue();

    foreach(['id', 'company_id', 'slug', 'weekend_days', 'check_in_time', 'check_out_time', 'created_at', 'updated_at'] as $column){
        expect(Schema::hasColumn('company_schedules', $column))->toBeTrue("Missing column: $column");
    }
});

test('company schedules table has foreign key', function () {
    $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'company_schedules' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

    expect($foreignKeys)->not->toBeEmpty("company_id should reference companies.id");
});