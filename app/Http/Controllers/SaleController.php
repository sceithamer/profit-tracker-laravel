<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\StorageUnit;
use App\Models\Platform;
use App\Models\User;
use App\Models\Category;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $sortBy = $request->get('sort', 'sale_date');
        $sortDir = $request->get('direction', 'desc');
        
        // Validate per_page values
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 25;
        }
        
        // Validate sortable columns
        $sortableColumns = [
            'sale_date', 'item_name', 'category_id', 'sale_price', 
            'fees', 'shipping_cost', 'platform_id', 'user_id', 'storage_unit_id'
        ];
        
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'sale_date';
        }
        
        // Validate sort direction
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        
        $query = Sale::with(['storageUnit', 'user', 'platform', 'category']);
        
        // Handle sorting by related models
        if ($sortBy === 'platform_id') {
            $query->join('platforms', 'sales.platform_id', '=', 'platforms.id')
                  ->orderBy('platforms.name', $sortDir)
                  ->select('sales.*');
        } elseif ($sortBy === 'user_id') {
            $query->join('users', 'sales.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortDir)
                  ->select('sales.*');
        } elseif ($sortBy === 'storage_unit_id') {
            $query->leftJoin('storage_units', 'sales.storage_unit_id', '=', 'storage_units.id')
                  ->orderBy('storage_units.name', $sortDir)
                  ->select('sales.*');
        } else {
            $query->orderBy($sortBy, $sortDir);
        }
        
        $sales = $query->paginate($perPage);
        $sales->appends($request->query());
        
        return view('sales.index', compact('sales', 'sortBy', 'sortDir', 'perPage'));
    }

    public function create()
    {
        $storageUnits = StorageUnit::where('status', 'active')->orderBy('name')->get();
        $platforms = Platform::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('sales.create', compact('storageUnits', 'platforms', 'users', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'storage_unit_id' => 'nullable|exists:storage_units,id',
            'user_id' => 'required|exists:users,id',
            'platform_id' => 'required|exists:platforms,id',
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sale_price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'fees' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0'
        ]);

        // Set defaults for optional numeric fields
        $validated['fees'] = $validated['fees'] ?? 0;
        $validated['shipping_cost'] = $validated['shipping_cost'] ?? 0;

        Sale::create($validated);

        return redirect()->route('dashboard')
                        ->with('success', 'Sale recorded successfully!');
    }

    public function show(Sale $sale)
    {
        $sale->load(['storageUnit', 'user', 'platform']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $storageUnits = StorageUnit::orderBy('name')->get();
        $platforms = Platform::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        return view('sales.edit', compact('sale', 'storageUnits', 'platforms', 'users', 'categories'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'storage_unit_id' => 'nullable|exists:storage_units,id',
            'user_id' => 'required|exists:users,id',
            'platform_id' => 'required|exists:platforms,id',
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sale_price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
            'fees' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0'
        ]);

        $validated['fees'] = $validated['fees'] ?? 0;
        $validated['shipping_cost'] = $validated['shipping_cost'] ?? 0;

        $sale->update($validated);

        return redirect()->route('sales.index')
                        ->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
                        ->with('success', 'Sale deleted successfully!');
    }
}
