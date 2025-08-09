<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StorageUnit;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Main statistics (matching All Sales page)
        $totalSales = Sale::count();
        $grossRevenue = Sale::sum('sale_price');
        $totalExpenses = Sale::sum('fees') + Sale::sum('shipping_cost') + 
                        StorageUnit::sum('cost') + Expense::sum('amount');
        $netRevenue = $grossRevenue - $totalExpenses;
        
        // Top 5 performing storage units by net revenue
        $topStorageUnits = StorageUnit::with(['sales', 'expenses'])
            ->get()
            ->map(function ($unit) {
                $unit->calculated_net_revenue = $unit->net_revenue;
                return $unit;
            })
            ->sortByDesc('calculated_net_revenue')
            ->take(5);
        
        // Top 5 performing sellers (users) by all-time net revenue
        $topSellers = User::with('sales.storageUnit')
            ->get()
            ->map(function ($user) {
                $userGrossRevenue = $user->sales->sum('sale_price');
                $userExpenses = $user->sales->sum('fees') + $user->sales->sum('shipping_cost');
                
                // Calculate storage unit costs proportionally for this user
                $storageUnitCosts = 0;
                foreach ($user->sales->groupBy('storage_unit_id') as $unitId => $unitSales) {
                    if ($unitId) {
                        $unit = $unitSales->first()->storageUnit;
                        if ($unit) {
                            $unitTotalSales = $unit->sales->count();
                            $userSalesFromUnit = $unitSales->count();
                            $proportionalCost = ($userSalesFromUnit / max($unitTotalSales, 1)) * $unit->cost;
                            $storageUnitCosts += $proportionalCost;
                        }
                    }
                }
                
                $user->user_net_revenue = $userGrossRevenue - $userExpenses - $storageUnitCosts;
                $user->user_sales_count = $user->sales->count();
                return $user;
            })
            ->where('user_sales_count', '>', 0)
            ->sortByDesc('user_net_revenue')
            ->take(5);
        
        // Recent activity - last 10 sales
        $recentSales = Sale::with(['user', 'platform', 'storageUnit'])
            ->orderBy('sale_date', 'desc')
            ->take(10)
            ->get();
        
        // Current month performance vs last month
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        $currentMonthSales = Sale::whereRaw("strftime('%Y-%m', sale_date) = ?", [$currentMonth]);
        $lastMonthSales = Sale::whereRaw("strftime('%Y-%m', sale_date) = ?", [$lastMonth]);
        
        $currentMonthStats = [
            'sales_count' => $currentMonthSales->count(),
            'gross_revenue' => $currentMonthSales->sum('sale_price'),
            'expenses' => $currentMonthSales->sum('fees') + $currentMonthSales->sum('shipping_cost'),
        ];
        $currentMonthStats['net_revenue'] = $currentMonthStats['gross_revenue'] - $currentMonthStats['expenses'];
        
        $lastMonthStats = [
            'sales_count' => $lastMonthSales->count(),
            'gross_revenue' => $lastMonthSales->sum('sale_price'),
            'expenses' => $lastMonthSales->sum('fees') + $lastMonthSales->sum('shipping_cost'),
        ];
        $lastMonthStats['net_revenue'] = $lastMonthStats['gross_revenue'] - $lastMonthStats['expenses'];
        
        return view('dashboard', compact(
            'totalSales',
            'grossRevenue', 
            'totalExpenses',
            'netRevenue',
            'topStorageUnits',
            'topSellers',
            'recentSales',
            'currentMonthStats',
            'lastMonthStats'
        ));
    }
}
