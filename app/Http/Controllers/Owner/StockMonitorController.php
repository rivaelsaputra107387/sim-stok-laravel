<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockMonitorController extends Controller
{
    /**
     * Stock movement overview dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $dateFrom = now()->subDays($period);

        // Overview statistics
        $stats = [
            'total_transactions' => StockTransaction::where('created_at', '>=', $dateFrom)->count(),
            'stock_in_count' => StockTransaction::where('type', 'in')
                ->where('created_at', '>=', $dateFrom)->count(),
            'stock_out_count' => StockTransaction::where('type', 'out')
                ->where('created_at', '>=', $dateFrom)->count(),
            'total_stock_in_value' => StockTransaction::where('type', 'in')
                ->where('created_at', '>=', $dateFrom)->sum('total_price'),
            'total_stock_out_value' => StockTransaction::where('type', 'out')
                ->where('created_at', '>=', $dateFrom)->sum('total_price'),
            'active_products' => Product::where('is_active', true)->count(),
        ];

        // Daily stock movement for chart
        $dailyMovement = StockTransaction::where('created_at', '>=', $dateFrom)
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out'),
                DB::raw('SUM(CASE WHEN type = "in" THEN total_price ELSE 0 END) as value_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN total_price ELSE 0 END) as value_out')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent transactions summary
        $recentTransactions = StockTransaction::with(['product.category', 'user', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top moving products in the period
        $topMovingProducts = Product::select('products.*')
            ->addSelect([
                'total_movement' => StockTransaction::selectRaw('SUM(quantity)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('created_at', '>=', $dateFrom),
                'total_transactions' => StockTransaction::selectRaw('COUNT(*)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('created_at', '>=', $dateFrom)
            ])
            ->with(['category', 'unit'])
            ->having('total_movement', '>', 0)
            ->orderBy('total_movement', 'desc')
            ->limit(10)
            ->get();

        // Stock alerts summary
        $stockAlerts = [
            'low_stock' => Product::whereRaw('current_stock <= minimum_stock AND current_stock > 0')
                ->where('is_active', true)->count(),
            'out_of_stock' => Product::where('current_stock', '<=', 0)
                ->where('is_active', true)->count(),
            'critical_stock' => Product::whereRaw('current_stock <= (minimum_stock * 0.5) AND current_stock > 0')
                ->where('is_active', true)->count(),
        ];

        return view('owner.stock-monitor.index', compact(
            'stats',
            'dailyMovement',
            'recentTransactions',
            'topMovingProducts',
            'stockAlerts',
            'period'
        ));
    }

    /**
     * Stock transactions history (read-only)
     */
    public function transactions(Request $request)
    {
        $query = StockTransaction::with(['product.category', 'product.unit', 'user', 'supplier'])
            ->select('stock_transactions.*');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })->orWhere('transaction_code', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->get('category_id'));
            });
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->get('supplier_id'));
        }

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->get('date_to'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'transaction_date');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'product_name') {
            $query->join('products', 'stock_transactions.product_id', '=', 'products.id')
                  ->orderBy('products.name', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Filter options
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $suppliers = Supplier::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Summary for the current filter
        $summary = $this->getTransactionSummary($request);

        return view('owner.stock-monitor.transactions', compact(
            'transactions',
            'categories',
            'suppliers',
            'summary'
        ));
    }

    /**
     * Stock movement trends analysis
     */
    public function trends(Request $request)
    {
        $period = $request->get('period', '90'); // days
        $groupBy = $request->get('group_by', 'daily'); // daily, weekly, monthly
        $dateFrom = now()->subDays($period);

        // Determine grouping format
        $dateFormat = match($groupBy) {
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d' // daily
        };

        $selectFormat = match($groupBy) {
            'weekly' => 'YEARWEEK(transaction_date) as period',
            'monthly' => 'DATE_FORMAT(transaction_date, "%Y-%m") as period',
            default => 'DATE(transaction_date) as period'
        };

        // Stock movement trends
        $trendData = StockTransaction::where('created_at', '>=', $dateFrom)
            ->select(
                DB::raw($selectFormat),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out'),
                DB::raw('SUM(CASE WHEN type = "in" THEN total_price ELSE 0 END) as value_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN total_price ELSE 0 END) as value_out'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Category-wise trends
        $categoryTrends = StockTransaction::join('products', 'stock_transactions.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('stock_transactions.created_at', '>=', $dateFrom)
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(CASE WHEN stock_transactions.type = "in" THEN stock_transactions.quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN stock_transactions.type = "out" THEN stock_transactions.quantity ELSE 0 END) as stock_out'),
                DB::raw('SUM(CASE WHEN stock_transactions.type = "in" THEN stock_transactions.total_price ELSE 0 END) as value_in'),
                DB::raw('SUM(CASE WHEN stock_transactions.type = "out" THEN stock_transactions.total_price ELSE 0 END) as value_out')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('value_out', 'desc')
            ->get();

        // Product performance trends
        $productTrends = Product::select('products.*')
            ->addSelect([
                'period_stock_in' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'in')
                    ->where('created_at', '>=', $dateFrom),
                'period_stock_out' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'out')
                    ->where('created_at', '>=', $dateFrom),
                'period_value_out' => StockTransaction::selectRaw('COALESCE(SUM(total_price), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'out')
                    ->where('created_at', '>=', $dateFrom),
                'avg_transaction_value' => StockTransaction::selectRaw('COALESCE(AVG(total_price), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'out')
                    ->where('created_at', '>=', $dateFrom)
            ])
            ->with(['category', 'unit'])
            ->having('period_stock_out', '>', 0)
            ->orderBy('period_value_out', 'desc')
            ->limit(20)
            ->get();

        // Velocity analysis (stock turnover)
        $velocityAnalysis = $this->calculateStockVelocity($dateFrom);

        // Trend insights
        $insights = $this->generateTrendInsights($trendData, $categoryTrends, $period);

        return view('owner.stock-monitor.trends', compact(
            'trendData',
            'categoryTrends',
            'productTrends',
            'velocityAnalysis',
            'insights',
            'period',
            'groupBy'
        ));
    }

    /**
     * Get transaction summary for filters
     */
    private function getTransactionSummary(Request $request)
    {
        $query = StockTransaction::query();

        // Apply same filters as main query
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })->orWhere('transaction_code', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->get('category_id'));
            });
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->get('supplier_id'));
        }

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->get('date_to'));
        }

        return [
            'total_transactions' => $query->count(),
            'total_stock_in' => $query->where('type', 'in')->sum('quantity'),
            'total_stock_out' => $query->where('type', 'out')->sum('quantity'),
            'total_value_in' => $query->where('type', 'in')->sum('total_price'),
            'total_value_out' => $query->where('type', 'out')->sum('total_price'),
        ];
    }

    /**
     * Calculate stock velocity (turnover rate)
     */
    private function calculateStockVelocity($dateFrom)
    {
        return Product::select('products.*')
            ->addSelect([
                'avg_stock' => StockTransaction::selectRaw('AVG((stock_before + stock_after) / 2)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('created_at', '>=', $dateFrom),
                'total_stock_out' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'out')
                    ->where('created_at', '>=', $dateFrom)
            ])
            ->with(['category', 'unit'])
            ->having('total_stock_out', '>', 0)
            ->having('avg_stock', '>', 0)
            ->get()
            ->map(function ($product) use ($dateFrom) {
                $days = now()->diffInDays($dateFrom);
                $product->velocity = $product->avg_stock > 0
                    ? round(($product->total_stock_out / $product->avg_stock) * (365 / $days), 2)
                    : 0;
                $product->turnover_days = $product->velocity > 0
                    ? round(365 / $product->velocity, 1)
                    : 0;
                return $product;
            })
            ->sortByDesc('velocity')
            ->take(15);
    }
}
