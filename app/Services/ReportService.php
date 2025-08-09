<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Sale;
use App\Models\StorageUnit;
use App\Models\User;

class ReportService
{
    /**
     * Get comprehensive monthly financial report for the specified year.
     */
    public function getMonthlyFinancialReport(int $year = null): array
    {
        $year = $year ?? date('Y');
        
        // Use a single optimized query with CTEs to minimize database round trips
        $monthlyData = DB::select("
            WITH months AS (
                SELECT 1 as month UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
                UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 
                UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
            ),
            storage_purchases AS (
                SELECT 
                    CAST(strftime('%m', purchase_date) AS INTEGER) as month,
                    COUNT(*) as units_bought,
                    SUM(cost) as total_spent
                FROM storage_units 
                WHERE strftime('%Y', purchase_date) = ?
                GROUP BY strftime('%m', purchase_date)
            ),
            sales_data AS (
                SELECT 
                    CAST(strftime('%m', sale_date) AS INTEGER) as month,
                    COUNT(*) as sales_count,
                    SUM(sale_price) as total_sales_gross,
                    SUM(fees) as total_fees,
                    SUM(shipping_cost) as total_shipping,
                    SUM(sale_price - fees - shipping_cost) as net_revenue
                FROM sales 
                WHERE strftime('%Y', sale_date) = ?
                GROUP BY strftime('%m', sale_date)
            ),
            expenses_data AS (
                SELECT 
                    CAST(strftime('%m', date) AS INTEGER) as month,
                    SUM(amount) as total_expenses
                FROM expenses 
                WHERE strftime('%Y', date) = ?
                GROUP BY strftime('%m', date)
            )
            SELECT 
                m.month,
                COALESCE(sp.units_bought, 0) as units_bought,
                COALESCE(sp.total_spent, 0) as total_spent,
                COALESCE(ed.total_expenses, 0) as total_expenses,
                COALESCE(sd.sales_count, 0) as sales_count,
                COALESCE(sd.total_fees, 0) as total_fees,
                COALESCE(sd.total_shipping, 0) as total_shipping,
                COALESCE(sd.total_sales_gross, 0) as total_sales_gross,
                COALESCE(sd.net_revenue, 0) as net_revenue
            FROM months m
            LEFT JOIN storage_purchases sp ON m.month = sp.month
            LEFT JOIN expenses_data ed ON m.month = ed.month
            LEFT JOIN sales_data sd ON m.month = sd.month
            ORDER BY m.month
        ", [(string) $year, (string) $year, (string) $year]);
        
        // Convert to associative array by month and calculate yearly totals
        $monthlyByKey = [];
        $yearlyTotals = [
            'units_bought' => 0,
            'total_spent' => 0,
            'total_expenses' => 0,
            'total_fees' => 0,
            'total_shipping' => 0,
            'total_sales_gross' => 0,
            'net_revenue' => 0
        ];
        
        foreach ($monthlyData as $data) {
            $monthlyByKey[$data->month] = [
                'units_bought' => $data->units_bought,
                'total_spent' => $data->total_spent,
                'total_expenses' => $data->total_expenses,
                'total_fees' => $data->total_fees,
                'total_shipping' => $data->total_shipping,
                'total_sales_gross' => $data->total_sales_gross,
                'net_revenue' => $data->net_revenue
            ];
            
            $yearlyTotals['units_bought'] += $data->units_bought;
            $yearlyTotals['total_spent'] += $data->total_spent;
            $yearlyTotals['total_expenses'] += $data->total_expenses;
            $yearlyTotals['total_fees'] += $data->total_fees;
            $yearlyTotals['total_shipping'] += $data->total_shipping;
            $yearlyTotals['total_sales_gross'] += $data->total_sales_gross;
            $yearlyTotals['net_revenue'] += $data->net_revenue;
        }
        
        return [
            'monthly' => $monthlyByKey,
            'yearly' => $yearlyTotals
        ];
    }
    
    /**
     * Get user performance metrics for the specified year.
     */
    public function getUserPerformanceReport(int $year = null): array
    {
        $year = $year ?? date('Y');
        
        // Get all users with their monthly sales data
        $userData = DB::select("
            SELECT 
                u.id as user_id,
                u.name as user_name,
                CAST(COALESCE(strftime('%m', s.sale_date), 0) AS INTEGER) as month,
                COUNT(s.id) as sales_count,
                SUM(COALESCE(s.sale_price, 0)) as gross_revenue,
                SUM(COALESCE(s.sale_price - s.fees - s.shipping_cost, 0)) as net_revenue
            FROM users u
            LEFT JOIN sales s ON u.id = s.user_id 
                AND strftime('%Y', s.sale_date) = ?
            GROUP BY u.id, u.name, strftime('%m', s.sale_date)
            ORDER BY u.name, month
        ", [(string) $year]);
        
        // Organize data by user
        $userMetrics = [];
        
        foreach ($userData as $data) {
            $userId = $data->user_id;
            
            if (!isset($userMetrics[$userId])) {
                $userMetrics[$userId] = [
                    'name' => $data->user_name,
                    'monthly' => array_fill(1, 12, [
                        'sales_count' => 0,
                        'gross_revenue' => 0,
                        'net_revenue' => 0
                    ]),
                    'yearly' => [
                        'sales_count' => 0,
                        'gross_revenue' => 0,
                        'net_revenue' => 0
                    ]
                ];
            }
            
            // Only process if there's actual month data
            if ($data->month > 0 && $data->sales_count > 0) {
                $userMetrics[$userId]['monthly'][$data->month] = [
                    'sales_count' => $data->sales_count,
                    'gross_revenue' => $data->gross_revenue,
                    'net_revenue' => $data->net_revenue
                ];
                
                $userMetrics[$userId]['yearly']['sales_count'] += $data->sales_count;
                $userMetrics[$userId]['yearly']['gross_revenue'] += $data->gross_revenue;
                $userMetrics[$userId]['yearly']['net_revenue'] += $data->net_revenue;
            }
        }
        
        return $userMetrics;
    }
    
    /**
     * Get storage unit performance metrics for the specified year.
     */
    public function getStorageUnitPerformanceReport(int $year = null): array
    {
        $year = $year ?? date('Y');
        
        // Get all storage units with their monthly sales data
        $unitData = DB::select("
            SELECT 
                su.id as storage_unit_id,
                su.name as storage_unit_name,
                CAST(COALESCE(strftime('%m', s.sale_date), 0) AS INTEGER) as month,
                COUNT(s.id) as sales_count,
                SUM(COALESCE(s.sale_price, 0)) as gross_revenue,
                SUM(COALESCE(s.sale_price - s.fees - s.shipping_cost, 0)) as net_revenue
            FROM storage_units su
            LEFT JOIN sales s ON su.id = s.storage_unit_id 
                AND strftime('%Y', s.sale_date) = ?
            GROUP BY su.id, su.name, strftime('%m', s.sale_date)
            ORDER BY su.name, month
        ", [(string) $year]);
        
        // Get unassigned sales data separately
        $unassignedData = DB::select("
            SELECT 
                CAST(strftime('%m', sale_date) AS INTEGER) as month,
                COUNT(*) as sales_count,
                SUM(sale_price) as gross_revenue,
                SUM(sale_price - fees - shipping_cost) as net_revenue
            FROM sales 
            WHERE storage_unit_id IS NULL 
                AND strftime('%Y', sale_date) = ?
            GROUP BY strftime('%m', sale_date)
            ORDER BY month
        ", [(string) $year]);
        
        // Organize data by storage unit
        $unitMetrics = [];
        
        foreach ($unitData as $data) {
            $unitId = $data->storage_unit_id;
            
            if (!isset($unitMetrics[$unitId])) {
                $unitMetrics[$unitId] = [
                    'name' => $data->storage_unit_name,
                    'monthly' => array_fill(1, 12, [
                        'sales_count' => 0,
                        'gross_revenue' => 0,
                        'net_revenue' => 0
                    ]),
                    'yearly' => [
                        'sales_count' => 0,
                        'gross_revenue' => 0,
                        'net_revenue' => 0
                    ]
                ];
            }
            
            // Only process if there's actual month data
            if ($data->month > 0 && $data->sales_count > 0) {
                $unitMetrics[$unitId]['monthly'][$data->month] = [
                    'sales_count' => $data->sales_count,
                    'gross_revenue' => $data->gross_revenue,
                    'net_revenue' => $data->net_revenue
                ];
                
                $unitMetrics[$unitId]['yearly']['sales_count'] += $data->sales_count;
                $unitMetrics[$unitId]['yearly']['gross_revenue'] += $data->gross_revenue;
                $unitMetrics[$unitId]['yearly']['net_revenue'] += $data->net_revenue;
            }
        }
        
        // Add unassigned sales as a special entry
        if (!empty($unassignedData)) {
            $unitMetrics['unassigned'] = [
                'name' => 'Unassigned Sales',
                'monthly' => array_fill(1, 12, [
                    'sales_count' => 0,
                    'gross_revenue' => 0,
                    'net_revenue' => 0
                ]),
                'yearly' => [
                    'sales_count' => 0,
                    'gross_revenue' => 0,
                    'net_revenue' => 0
                ]
            ];
            
            foreach ($unassignedData as $data) {
                $unitMetrics['unassigned']['monthly'][$data->month] = [
                    'sales_count' => $data->sales_count,
                    'gross_revenue' => $data->gross_revenue,
                    'net_revenue' => $data->net_revenue
                ];
                
                $unitMetrics['unassigned']['yearly']['sales_count'] += $data->sales_count;
                $unitMetrics['unassigned']['yearly']['gross_revenue'] += $data->gross_revenue;
                $unitMetrics['unassigned']['yearly']['net_revenue'] += $data->net_revenue;
            }
        }
        
        return $unitMetrics;
    }
    
    /**
     * Get available years from sales and storage unit data.
     */
    public function getAvailableYears(): array
    {
        $salesYears = DB::select("
            SELECT DISTINCT strftime('%Y', sale_date) as year 
            FROM sales 
            WHERE sale_date IS NOT NULL
            ORDER BY year DESC
        ");
        
        $unitYears = DB::select("
            SELECT DISTINCT strftime('%Y', purchase_date) as year 
            FROM storage_units 
            WHERE purchase_date IS NOT NULL
            ORDER BY year DESC
        ");
        
        $allYears = [];
        foreach ($salesYears as $row) {
            if ($row->year) $allYears[] = (int) $row->year;
        }
        foreach ($unitYears as $row) {
            if ($row->year) $allYears[] = (int) $row->year;
        }
        
        $allYears = array_unique($allYears);
        rsort($allYears);
        
        return $allYears;
    }
}