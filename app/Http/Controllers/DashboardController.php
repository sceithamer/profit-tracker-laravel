<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StorageUnit;
use App\Models\Sale;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $storageUnits = StorageUnit::with(['sales', 'expenses'])->get();
        $unassignedSales = Sale::whereNull('storage_unit_id')->with(['user', 'platform'])->get();
        $unassignedExpenses = Expense::whereNull('storage_unit_id')->get();
        
        $totalInvestment = $storageUnits->sum('cost') + Expense::sum('amount');
        $totalRevenue = Sale::sum('sale_price') - Sale::sum('fees') - Sale::sum('shipping_cost');
        $totalProfit = $totalRevenue - $totalInvestment;
        $overallROI = $totalInvestment > 0 ? round(($totalProfit / $totalInvestment) * 100, 2) : 0;
        
        return view('dashboard', compact(
            'storageUnits', 
            'unassignedSales', 
            'unassignedExpenses',
            'totalInvestment',
            'totalRevenue', 
            'totalProfit',
            'overallROI'
        ));
    }
}
