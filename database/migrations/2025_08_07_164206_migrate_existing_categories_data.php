<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Sale;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Extract unique categories from existing sales
        $uniqueCategories = DB::table('sales')
            ->select('item_category')
            ->whereNotNull('item_category')
            ->where('item_category', '!=', '')
            ->groupBy('item_category')
            ->pluck('item_category')
            ->filter()
            ->map(function ($category) {
                // Clean and normalize category names
                return trim($category);
            })
            ->filter(function ($category) {
                return !empty($category);
            })
            ->unique()
            ->sort()
            ->values();

        // Create categories
        foreach ($uniqueCategories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }

        // Create a default "Uncategorized" category for null/empty categories
        $uncategorizedCategory = Category::firstOrCreate(['name' => 'Uncategorized']);

        // Update sales with category_id
        foreach ($uniqueCategories as $categoryName) {
            $category = Category::where('name', $categoryName)->first();
            
            DB::table('sales')
                ->where('item_category', $categoryName)
                ->update(['category_id' => $category->id]);
        }

        // Update sales with null or empty categories to use "Uncategorized"
        DB::table('sales')
            ->where(function ($query) {
                $query->whereNull('item_category')
                      ->orWhere('item_category', '');
            })
            ->update(['category_id' => $uncategorizedCategory->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset category_id to null for all sales
        DB::table('sales')->update(['category_id' => null]);
        
        // Delete all categories except those that might have been manually created
        // We'll only delete categories that match the pattern of migrated data
        Category::whereIn('name', [
            'Uncategorized'
        ])->delete();
        
        // Note: We don't delete other categories as they might have been manually added
        // after the migration. Manual cleanup would be required if full rollback is needed.
    }
};
