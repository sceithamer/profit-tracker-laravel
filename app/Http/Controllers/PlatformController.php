<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Platform;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::withCount('sales')->get();
        return view('platforms.index', compact('platforms'));
    }

    public function create()
    {
        return view('platforms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name'
        ]);

        Platform::create($validated);

        return redirect()->route('platforms.index')
                        ->with('success', 'Platform created successfully!');
    }

    public function show(Request $request, Platform $platform)
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
            'sale_date', 'item_name', 'item_category', 'sale_price', 
            'fees', 'shipping_cost', 'user_id', 'storage_unit_id'
        ];
        
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'sale_date';
        }
        
        // Validate sort direction
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        
        $query = $platform->sales()->with(['storageUnit', 'user']);
        
        // Handle sorting by related models
        if ($sortBy === 'user_id') {
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
        
        return view('platforms.show', compact('platform', 'sales', 'sortBy', 'sortDir', 'perPage'));
    }

    public function edit(Platform $platform)
    {
        return view('platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name,' . $platform->id
        ]);

        $platform->update($validated);

        return redirect()->route('platforms.index')
                        ->with('success', 'Platform updated successfully!');
    }

    public function destroy(Platform $platform)
    {
        if ($platform->sales()->count() > 0) {
            return redirect()->route('platforms.index')
                            ->with('error', 'Cannot delete platform with existing sales!');
        }

        $platform->delete();

        return redirect()->route('platforms.index')
                        ->with('success', 'Platform deleted successfully!');
    }
}
