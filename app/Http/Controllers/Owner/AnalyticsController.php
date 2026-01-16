<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display business summary report
     */
    public function summary(Request $request): View
    {
        $period = $request->get('period', 'this_month');
        $dateRange = $this->getDateRange($period);

        // Business KPIs
        $totalProducts = Product::active()->count();
        $totalInventoryValue = Product::active()->sum(DB::raw('current_stock * purchase_price'));
        $lowStockProducts = Product::lowStock()->count();
        $outOfStockProducts = Product::outOfStock()->count();

        // Transaction Summary
        $stockInCount = StockTransaction::stockIn()
            ->whereBetween('transaction_date', $dateRange)
            ->count();

        $stockOutCount = StockTransaction::stockOut()
            ->whereBetween('transaction_date', $dateRange)
            ->count();

        $stockInValue = StockTransaction::stockIn()
            ->whereBetween('transaction_date', $dateRange)
            ->sum('total_price');

        $stockOutValue = StockTransaction::stockOut()
            ->whereBetween('transaction_date', $dateRange)
            ->sum('total_price');

        // Top Categories by Value
        $topCategories = Category::withCount('products')
            ->with(['products' => function($query) {
                $query->select('category_id', DB::raw('SUM(current_stock * purchase_price) as inventory_value'))
                      ->groupBy('category_id');
            }])
            ->get()
            ->sortByDesc(function($category) {
                return $category->products->sum('inventory_value');
            })
            ->take(5);

        return view('owner.reports.summary', compact(
            'totalProducts',
            'totalInventoryValue',
            'lowStockProducts',
            'outOfStockProducts',
            'stockInCount',
            'stockOutCount',
            'stockInValue',
            'stockOutValue',
            'topCategories',
            'period'
        ));
    }

    /**
     * Display detailed report for specific period
     */
    public function detailed(Request $request): View
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $query = StockTransaction::with(['product.category', 'product.unit', 'supplier', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($request->filled('category_id')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(50);

        // Summary Statistics
        $summary = [
            'total_transactions' => $query->count(),
            'stock_in_count' => (clone $query)->stockIn()->count(),
            'stock_out_count' => (clone $query)->stockOut()->count(),
            'stock_in_value' => (clone $query)->stockIn()->sum('total_price'),
            'stock_out_value' => (clone $query)->stockOut()->sum('total_price'),
        ];

        $categories = Category::active()->get();

        return view('owner.reports.detailed', compact(
            'transactions',
            'summary',
            'categories',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display period comparison report
     */
    public function comparison(Request $request): View
    {
        $currentPeriod = $request->get('current_period', 'this_month');
        $previousPeriod = $request->get('previous_period', 'last_month');

        $currentRange = $this->getDateRange($currentPeriod);
        $previousRange = $this->getDateRange($previousPeriod);

        // Current Period Data
        $currentData = $this->getPeriodData($currentRange);

        // Previous Period Data
        $previousData = $this->getPeriodData($previousRange);

        // Calculate Differences
        $comparison = [
            'stock_in_diff' => $this->calculatePercentageChange(
                $previousData['stock_in_count'],
                $currentData['stock_in_count']
            ),
            'stock_out_diff' => $this->calculatePercentageChange(
                $previousData['stock_out_count'],
                $currentData['stock_out_count']
            ),
            'stock_in_value_diff' => $this->calculatePercentageChange(
                $previousData['stock_in_value'],
                $currentData['stock_in_value']
            ),
            'stock_out_value_diff' => $this->calculatePercentageChange(
                $previousData['stock_out_value'],
                $currentData['stock_out_value']
            ),
        ];

        return view('owner.reports.comparison', compact(
            'currentData',
            'previousData',
            'comparison',
            'currentPeriod',
            'previousPeriod'
        ));
    }

    /**
     * Export owner reports
     */
    public function export(Request $request): Response
    {
        $type = $request->get('type', 'summary');
        $format = $request->get('format', 'pdf');

        switch ($type) {
            case 'summary':
                $data = $this->getSummaryReportData($request);
                $view = 'owner.reports.export.summary';
                break;
            case 'detailed':
                $data = $this->getDetailedReportData($request);
                $view = 'owner.reports.export.detailed';
                break;
            case 'comparison':
                $data = $this->getComparisonReportData($request);
                $view = 'owner.reports.export.comparison';
                break;
            default:
                abort(404);
        }

        if ($format === 'pdf') {
            $pdf = PDF::loadView($view, $data);
            return $pdf->download("owner_report_{$type}_" . now()->format('Y-m-d') . '.pdf');
        }

        // Add Excel export logic here if needed
        abort(404);
    }

    /**
     * Get date range based on period
     */
    private function getDateRange(string $period): array
    {
        switch ($period) {
            case 'today':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'yesterday':
                return [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
            case 'this_week':
                return [now()->startOfWeek(), now()->endOfWeek()];
            case 'last_week':
                return [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()];
            case 'this_month':
                return [now()->startOfMonth(), now()->endOfMonth()];
            case 'last_month':
                return [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()];
            case 'this_year':
                return [now()->startOfYear(), now()->endOfYear()];
            case 'last_year':
                return [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()];
            default:
                return [now()->startOfMonth(), now()->endOfMonth()];
        }
    }

    /**
     * Get period data for comparison
     */
    private function getPeriodData(array $dateRange): array
    {
        return [
            'stock_in_count' => StockTransaction::stockIn()
                ->whereBetween('transaction_date', $dateRange)
                ->count(),
            'stock_out_count' => StockTransaction::stockOut()
                ->whereBetween('transaction_date', $dateRange)
                ->count(),
            'stock_in_value' => StockTransaction::stockIn()
                ->whereBetween('transaction_date', $dateRange)
                ->sum('total_price'),
            'stock_out_value' => StockTransaction::stockOut()
                ->whereBetween('transaction_date', $dateRange)
                ->sum('total_price'),
        ];
    }

    /**
     * Calculate percentage change
     */
    private function calculatePercentageChange($previous, $current): array
    {
        if ($previous == 0) {
            $percentage = $current > 0 ? 100 : 0;
        } else {
            $percentage = (($current - $previous) / $previous) * 100;
        }

        return [
            'value' => $current - $previous,
            'percentage' => round($percentage, 2),
            'is_positive' => $percentage >= 0
        ];
    }
}
