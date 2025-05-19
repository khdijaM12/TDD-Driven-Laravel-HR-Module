<?php

namespace Tests\Unit\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CompaniesMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_companies_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasTable('companies'));

        foreach (['id', 'name_en', 'name_ar', 'logo', 'website', 'status', 'created_at', 'updated_at'] as $column) {
            $this->assertTrue(
                Schema::hasColumn('companies', $column),
                "Missing column: $column"
            );
        }
    }
}
