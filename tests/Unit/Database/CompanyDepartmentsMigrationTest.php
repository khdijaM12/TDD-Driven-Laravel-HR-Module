<?php

namespace Tests\Unit\Database;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CompanyDepartmentsMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_departments_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('company_departments'));

        foreach(['id', 'company_id', 'name_en', 'name_ar', 'created_at', 'updated_at'] as $column){
            $this->assertTrue(
                Schema::hasColumn('company_departments', $column),
                "Missing column: $column"
            );
        }

    }

    public function test_company_departments_table_has_foreign_key()
    {
        $foreignKeys = DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'company_departments' AND COLUMN_NAME = 'company_id' AND REFERENCED_TABLE_NAME = 'companies'");

            $this->assertNotEmpty($foreignKeys, "company_id should reference companies.id");
    }

}
