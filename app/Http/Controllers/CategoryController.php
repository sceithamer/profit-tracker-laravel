<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with sales count.
     */
    public function index()
    {
        $categories = Category::withCount('sales')
                             ->orderBy('name')
                             ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name'
        ]);

        Category::create($request->only('name'));

        return redirect()->route('categories.index')
                         ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category with its sales.
     */
    public function show(Request $request, Category $category)
    {
        $perPage = $request->get('per_page', 25);
        $sortBy = $request->get('sort', 'sale_date');
        $sortDir = $request->get('direction', 'desc');
        
        // Validate inputs
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 25;
        }
        
        $sortableColumns = [
            'sale_date', 'item_name', 'sale_price', 
            'fees', 'shipping_cost', 'platform_id', 'user_id', 'storage_unit_id'
        ];
        
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'sale_date';
        }
        
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        
        // Get paginated sales for this category
        $sales = $category->sales()
                         ->with(['storageUnit', 'user', 'platform', 'category'])
                         ->orderBy($sortBy, $sortDir)
                         ->paginate($perPage);
                         
        return view('categories.show', compact('category', 'sales', 'sortBy', 'sortDir', 'perPage'));
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category->id)
            ]
        ]);

        $category->update($request->only('name'));

        return redirect()->route('categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')
                             ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                             ->with('error', $e->getMessage());
        }
    }

    /**
     * API endpoint to get all categories for dropdowns.
     */
    public function api()
    {
        return Category::orderBy('name')->get(['id', 'name']);
    }
}