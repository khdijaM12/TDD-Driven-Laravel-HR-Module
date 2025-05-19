<?php

namespace Tests\Unit\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompanySchedulesMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_schedules_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('company_schedules'));

        foreach(['id', 'company_id', 'slug', 'weekend_days', 'check_in_time', 'check_out_time', 'created_at', 'updated_at'] as $column){
            $this->assertTrue(
                Schema::hasColumn('company_schedules', $column),
                "Missing column: $column"
            );
        }
    }

    public function test_company_schedules_table_has_foreign_key()
    {
        $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'company_schedules' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

    $this->assertNotEmpty($foreignKeys, "company_id should reference companies.id");
    }
}
