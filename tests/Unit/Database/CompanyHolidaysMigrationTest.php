<?php

namespace Tests\Unit\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompanyHolidaysMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_holidays_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('company_holidays'));

        foreach(['id', 'occasion', 'date_from', 'date_to', 'company_id', 'branch_id', 'created_at', 'updated_at'] as $column){
            $this->assertTrue(
                Schema::hasColumn('company_holidays', $column),
                "Missing column: $column"
            );
        }
    }

    public function test_company_holidays_table_has_foreign_keys()
    {
        $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_holidays' AND COLUMN_NAME IN ('company_id', 'branch_id')");

        $this->assertNotEmpty($foreignKeys, "company_id or branch_id should reference parent tables");
    }
}
