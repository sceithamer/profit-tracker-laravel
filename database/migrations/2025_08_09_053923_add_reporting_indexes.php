<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes optimized for reporting queries on SQLite
        DB::unprepared('
            CREATE INDEX IF NOT EXISTS idx_sales_sale_date_year 
            ON sales (strftime("%Y", sale_date));
            
            CREATE INDEX IF NOT EXISTS idx_sales_sale_date_month 
            ON sales (strftime("%Y-%m", sale_date));
            
            CREATE INDEX IF NOT EXISTS idx_storage_units_purchase_date_year 
            ON storage_units (strftime("%Y", purchase_date));
            
            CREATE INDEX IF NOT EXISTS idx_storage_units_purchase_date_month 
            ON storage_units (strftime("%Y-%m", purchase_date));
            
            CREATE INDEX IF NOT EXISTS idx_sales_user_date 
            ON sales (user_id, sale_date);
            
            CREATE INDEX IF NOT EXISTS idx_sales_storage_unit_date 
            ON sales (storage_unit_id, sale_date);
            
            CREATE INDEX IF NOT EXISTS idx_sales_year_month_composite
            ON sales (strftime("%Y", sale_date), strftime("%m", sale_date));
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the reporting indexes
        DB::unprepared('
            DROP INDEX IF EXISTS idx_sales_sale_date_year;
            DROP INDEX IF EXISTS idx_sales_sale_date_month;
            DROP INDEX IF EXISTS idx_storage_units_purchase_date_year;
            DROP INDEX IF EXISTS idx_storage_units_purchase_date_month;
            DROP INDEX IF EXISTS idx_sales_user_date;
            DROP INDEX IF EXISTS idx_sales_storage_unit_date;
            DROP INDEX IF EXISTS idx_sales_year_month_composite;
        ');
    }
};
