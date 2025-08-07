<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StorageUnit;

class StorageUnitController extends Controller
{
    public function index()
    {
        $storageUnits = StorageUnit::with(['sales', 'expenses'])->get();
        return view('storage-units.index', compact('storageUnits'));
    }

    public function create()
    {
        return view('storage-units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,sold_out,archived'
        ]);

        StorageUnit::create($validated);

        return redirect()->route('storage-units.index')
                        ->with('success', 'Storage unit created successfully!');
    }

    public function show(Request $request, StorageUnit $storageUnit)
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
            'fees', 'shipping_cost', 'user_id', 'platform_id'
        ];
        
        if (!in_array($sortBy, $sortableColumns)) {
            $sortBy = 'sale_date';
        }
        
        // Validate sort direction
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }
        
        $query = $storageUnit->sales()->with(['user', 'platform']);
        
        // Handle sorting by related models
        if ($sortBy === 'user_id') {
            $query->join('users', 'sales.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortDir)
                  ->select('sales.*');
        } elseif ($sortBy === 'platform_id') {
            $query->join('platforms', 'sales.platform_id', '=', 'platforms.id')
                  ->orderBy('platforms.name', $sortDir)
                  ->select('sales.*');
        } else {
            $query->orderBy($sortBy, $sortDir);
        }
        
        $sales = $query->paginate($perPage);
        $sales->appends($request->query());
        
        $storageUnit->load(['expenses']);
        
        return view('storage-units.show', compact('storageUnit', 'sales', 'sortBy', 'sortDir', 'perPage'));
    }

    public function edit(StorageUnit $storageUnit)
    {
        return view('storage-units.edit', compact('storageUnit'));
    }

    public function update(Request $request, StorageUnit $storageUnit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,sold_out,archived'
        ]);

        $storageUnit->update($validated);

        return redirect()->route('storage-units.index')
                        ->with('success', 'Storage unit updated successfully!');
    }

    public function destroy(StorageUnit $storageUnit)
    {
        $storageUnit->delete();

        return redirect()->route('storage-units.index')
                        ->with('success', 'Storage unit deleted successfully!');
    }
}
