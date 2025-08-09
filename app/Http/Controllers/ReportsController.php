<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportsController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    /**
     * Display the reports dashboard with financial metrics.
     */
    public function index(Request $request)
    {
        // Validate and get the year parameter
        $year = $request->get('year');
        $availableYears = $this->reportService->getAvailableYears();
        
        // Default to current year if no year specified or invalid year
        if (!$year || !in_array((int) $year, $availableYears)) {
            $year = !empty($availableYears) ? $availableYears[0] : date('Y');
        }
        $year = (int) $year;
        
        // Get all report data
        $financialOverview = $this->reportService->getMonthlyFinancialReport($year);
        $userPerformance = $this->reportService->getUserPerformanceReport($year);
        $storageUnitPerformance = $this->reportService->getStorageUnitPerformanceReport($year);
        
        return view('reports.index', compact(
            'year',
            'availableYears',
            'financialOverview',
            'userPerformance',
            'storageUnitPerformance'
        ));
    }
}
