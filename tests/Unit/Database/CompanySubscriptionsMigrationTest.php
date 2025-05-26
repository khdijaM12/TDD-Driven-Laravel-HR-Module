<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('company subscriptions table has expected columns', function () {
    expect(Schema::hasTable('company_subscriptions'))->toBeTrue();

    foreach(['id', 'company_id', 'subscribe_start', 'subscribe_end', 'number_employees', 'created_at', 'updated_at'] as $column){
        expect(Schema::hasColumn('company_subscriptions', $column))->toBeTrue("Miising column: $column");
    }
});

test('company subscriptions table has foreign key', function () {
    $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_subscriptions' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

    expect($foreignKeys)->not->toBeEmpty("company_id should reference companies.id");
});