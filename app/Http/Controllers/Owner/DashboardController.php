<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\StockAlert;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Executive Dashboard - Main Dashboard untuk Owner
     */
    public function index(Request $request)
    {
        // KPI Data
        $kpiData = $this->getKPIData();

        // Business Overview
        $businessOverview = $this->getBusinessOverview();

        // Chart Data untuk grafik
        $chartData = $this->getChartData();

        // Critical Alerts
        $criticalAlerts = $this->getCriticalAlerts();

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        return view('owner.dashboard', compact(
            'kpiData',
            'businessOverview',
            'chartData',
            'criticalAlerts',
            'recentActivities'
        ));
    }

    /**
     * Get KPI Data - Key Performance Indicators
     */
    public function getKPIData()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        // Total Products
        $totalProducts = Product::active()->count();
        $totalProductsLastMonth = Product::where('created_at', '<', $currentMonth)->active()->count();
        $productsGrowth = $this->calculateGrowthPercentage($totalProducts, $totalProductsLastMonth);

        // Total Inventory Value
        $inventoryValue = Product::active()
            ->select(DB::raw('SUM(current_stock * purchase_price) as total_value'))
            ->first()
            ->total_value ?? 0;

        // Current Month Transactions
        $currentMonthTransactions = StockTransaction::where('transaction_date', '>=', $currentMonth)->count();
        $previousMonthTransactions = StockTransaction::whereBetween('transaction_date', [
            $previousMonth,
            $currentMonth->copy()->subSecond()
        ])->count();
        $transactionsGrowth = $this->calculateGrowthPercentage($currentMonthTransactions, $previousMonthTransactions);

        // Stock Alerts Count
        $totalAlerts = StockAlert::unread()->count();
        $criticalAlerts = StockAlert::unread()->where('type', 'out_of_stock')->count();

        // Low Stock Products
        $lowStockCount = Product::lowStock()->count();
        $outOfStockCount = Product::outOfStock()->count();

        // Stock In/Out This Month
        $stockInValue = StockTransaction::stockIn()
            ->where('transaction_date', '>=', $currentMonth)
            ->sum('total_price');

        $stockOutValue = StockTransaction::stockOut()
            ->where('transaction_date', '>=', $currentMonth)
            ->sum('total_price');

        return [
            'total_products' => [
                'value' => $totalProducts,
                'growth' => $productsGrowth,
                'label' => 'Total Produk'
            ],
            'inventory_value' => [
                'value' => $inventoryValue,
                'formatted' => 'Rp ' . number_format($inventoryValue, 0, ',', '.'),
                'label' => 'Nilai Inventaris'
            ],
            'monthly_transactions' => [
                'value' => $currentMonthTransactions,
                'growth' => $transactionsGrowth,
                'label' => 'Transaksi Bulan Ini'
            ],
            'stock_alerts' => [
                'total' => $totalAlerts,
                'critical' => $criticalAlerts,
                'label' => 'Peringatan Stok'
            ],
            'stock_status' => [
                'low_stock' => $lowStockCount,
                'out_of_stock' => $outOfStockCount,
                'normal' => $totalProducts - $lowStockCount - $outOfStockCount
            ],
            'stock_movement' => [
                'stock_in' => $stockInValue,
                'stock_out' => $stockOutValue,
                'balance' => $stockInValue - $stockOutValue
            ]
        ];
    }

    /**
     * Get Business Overview Data
     */
    public function getBusinessOverview()
    {
        // Top Categories by Product Count
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        // Top Suppliers by Transaction Value
        $topSuppliers = Supplier::select('suppliers.*')
            ->join('stock_transactions', 'suppliers.id', '=', 'stock_transactions.supplier_id')
            ->select('suppliers.name', DB::raw('SUM(stock_transactions.total_price) as total_value'))
            ->where('stock_transactions.type', StockTransaction::TYPES['IN'])
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get();

        // Most Active Products (by transaction frequency)
        $mostActiveProducts = Product::select('products.*')
            ->join('stock_transactions', 'products.id', '=', 'stock_transactions.product_id')
            ->select('products.name', 'products.code', DB::raw('COUNT(stock_transactions.id) as transaction_count'))
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();

        // User Activity Summary
        $userActivity = User::select('users.name', 'users.role')
            ->join('stock_transactions', 'users.id', '=', 'stock_transactions.user_id')
            ->select('users.name', 'users.role', DB::raw('COUNT(stock_transactions.id) as transaction_count'))
            ->where('stock_transactions.created_at', '>=', Carbon::now()->startOfMonth())
            ->groupBy('users.id', 'users.name', 'users.role')
            ->orderBy('transaction_count', 'desc')
            ->get();

        return [
            'top_categories' => $topCategories,
            'top_suppliers' => $topSuppliers,
            'most_active_products' => $mostActiveProducts,
            'user_activity' => $userActivity
        ];
    }

    /**
     * Get Chart Data untuk Dashboard
     */
    public function getChartData()
    {
        // Stock Movement Chart (Last 6 Months)
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();

        $stockMovementData = StockTransaction::select(
                DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out')
            )
            ->where('transaction_date', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Inventory Value Trend
        $inventoryTrend = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i)->endOfMonth();
            $value = Product::where('created_at', '<=', $date)
                ->select(DB::raw('SUM(current_stock * purchase_price) as total_value'))
                ->first()
                ->total_value ?? 0;

            $inventoryTrend->push([
                'month' => $date->format('Y-m'),
                'value' => $value
            ]);
        }

        // Category Distribution
        $categoryDistribution = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'value' => $category->products_count
                ];
            });

        // Stock Status Distribution
        $stockStatus = [
            'Normal' => Product::whereRaw('current_stock > minimum_stock')->count(),
            'Stok Rendah' => Product::lowStock()->count(),
            'Habis' => Product::outOfStock()->count()
        ];

        return [
            'stock_movement' => $stockMovementData,
            'inventory_trend' => $inventoryTrend,
            'category_distribution' => $categoryDistribution,
            'stock_status' => $stockStatus
        ];
    }

    /**
     * Get Critical Alerts for Owner
     */
    public function getCriticalAlerts()
    {
        return StockAlert::with('product')
            ->unread()
            ->orderBy('alert_date', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get Recent Activities
     */
    public function getRecentActivities()
    {
        return StockTransaction::with(['product', 'user', 'supplier'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get Business Performance Metrics
     */
    public function getBusinessMetrics()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();

        // Transaction Volume
        $currentTransactionVolume = StockTransaction::where('transaction_date', '>=', $currentMonth)->count();
        $previousTransactionVolume = StockTransaction::whereBetween('transaction_date', [
            $previousMonth,
            $currentMonth->copy()->subSecond()
        ])->count();

        // Average Transaction Value
        $avgTransactionValue = StockTransaction::where('transaction_date', '>=', $currentMonth)
            ->avg('total_price') ?? 0;

        // Stock Turnover (simplified)
        $stockOut = StockTransaction::stockOut()
            ->where('transaction_date', '>=', $currentMonth)
            ->sum('quantity');

        $avgStock = Product::avg('current_stock') ?? 1;
        $stockTurnover = $avgStock > 0 ? $stockOut / $avgStock : 0;

        // Most Profitable Categories
        $profitableCategories = Category::select('categories.name')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('stock_transactions', 'products.id', '=', 'stock_transactions.product_id')
            ->select('categories.name',
                DB::raw('SUM(stock_transactions.total_price) as total_revenue'),
                DB::raw('COUNT(stock_transactions.id) as transaction_count')
            )
            ->where('stock_transactions.type', StockTransaction::TYPES['OUT'])
            ->where('stock_transactions.transaction_date', '>=', $currentMonth)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get();

        return [
            'transaction_volume' => [
                'current' => $currentTransactionVolume,
                'previous' => $previousTransactionVolume,
                'growth' => $this->calculateGrowthPercentage($currentTransactionVolume, $previousTransactionVolume)
            ],
            'avg_transaction_value' => $avgTransactionValue,
            'stock_turnover' => $stockTurnover,
            'profitable_categories' => $profitableCategories
        ];
    }

    /**
     * Helper function to calculate growth percentage
     */
    private function calculateGrowthPercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Get Dashboard Notifications untuk Owner
     */
    public function getNotifications()
    {
        $notifications = [];

        // Critical Stock Alerts
        $criticalStock = Product::outOfStock()->count();
        if ($criticalStock > 0) {
            $notifications[] = [
                'type' => 'critical',
                'icon' => 'alert-triangle',
                'title' => 'Stok Habis',
                'message' => "{$criticalStock} produk habis dan perlu segera diisi ulang",
                'action_url' => route('owner.products.out-of-stock')
            ];
        }

        // Low Stock Warning
        $lowStock = Product::lowStock()->count();
        if ($lowStock > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'alert-circle',
                'title' => 'Stok Rendah',
                'message' => "{$lowStock} produk memiliki stok di bawah minimum",
                'action_url' => route('owner.products.low-stock')
            ];
        }

        // High Transaction Volume Alert
        $todayTransactions = StockTransaction::whereDate('transaction_date', Carbon::today())->count();
        $avgDailyTransactions = StockTransaction::where('transaction_date', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(transaction_date)'))
            ->select(DB::raw('COUNT(*) as daily_count'))
            ->avg('daily_count') ?? 0;

        if ($todayTransactions > ($avgDailyTransactions * 1.5)) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'trending-up',
                'title' => 'Aktivitas Tinggi',
                'message' => "Transaksi hari ini ({$todayTransactions}) lebih tinggi dari rata-rata",
                'action_url' => route('owner.transactions.today')
            ];
        }

        return $notifications;
    }

    /**
     * Get Quick Stats untuk Widget
     */
    public function getQuickStats()
    {
        return [
            'total_products' => Product::active()->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'total_suppliers' => Supplier::where('is_active', true)->count(),
            'total_users' => User::where('is_active', true)->count(),
            'unread_alerts' => StockAlert::unread()->count(),
            'today_transactions' => StockTransaction::whereDate('transaction_date', Carbon::today())->count(),
            'inventory_value' => Product::select(DB::raw('SUM(current_stock * purchase_price) as total'))->first()->total ?? 0,
            'monthly_revenue' => StockTransaction::stockOut()
                ->where('transaction_date', '>=', Carbon::now()->startOfMonth())
                ->sum('total_price')
        ];
    }
}
