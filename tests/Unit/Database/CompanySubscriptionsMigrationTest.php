<?php

namespace Tests\Unit\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompanySubscriptionsMigrationTest extends TestCase
{
     use RefreshDatabase;

    public function test_company_subscriptions_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('company_subscriptions'));

        foreach(['id', 'company_id', 'subscribe_start', 'subscribe_end', 'number_employees', 'created_at', 'updated_at'] as $column){
            $this->assertTrue(
                Schema::hasColumn('company_subscriptions', $column),
                "Miising column: $column"
            );
        }
    }

    public function test_company_subscriptions_table_has_foreign_key()
    {
        $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_subscriptions' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

        $this->assertNotEmpty($foreignKeys, "company_id should reference companies.id");
    }

}
