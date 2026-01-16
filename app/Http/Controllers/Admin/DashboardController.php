<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use App\Models\StockAlert;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ====================================
        // STATISTICS CARDS
        // ====================================
        $totalProducts = Product::count();
        $activeProducts = Product::active()->count();
        $totalCategories = Category::where('is_active', true)->count();
        $totalSuppliers = Supplier::where('is_active', true)->count();

        // ====================================
        // STOCK STATUS MONITORING
        // ====================================
        $lowStockProducts = Product::lowStock()->count();
        $outOfStockProducts = Product::outOfStock()->count();

        // REVISED: Count products with expired batches
        $productsWithExpiredBatches = Product::hasExpiredBatches()->count();

        // REVISED: Count products with near expiry batches
        $productsWithNearExpiryBatches = Product::hasNearExpiryBatches()->count();

        // ====================================
        // RECENT STOCK TRANSACTIONS
        // ====================================
        $recentTransactions = StockTransaction::with(['product', 'supplier', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ====================================
        // STOCK MOVEMENT SUMMARY (THIS MONTH)
        // ====================================
        $thisMonthMovement = StockTransaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->selectRaw('
                type,
                COUNT(*) as total_transactions,
                SUM(quantity) as total_quantity
            ')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Format movement data
        $stockInMovement = $thisMonthMovement->get('in', (object)[
            'total_transactions' => 0,
            'total_quantity' => 0
        ]);

        $stockOutMovement = $thisMonthMovement->get('out', (object)[
            'total_transactions' => 0,
            'total_quantity' => 0
        ]);

        // ====================================
        // STOCK ALERTS
        // ====================================
        $stockAlerts = StockAlert::with('product')
            ->unread()
            ->orderBy('alert_date', 'desc')
            ->limit(10)
            ->get();

        // Count alerts by type
        $alertCounts = StockAlert::unread()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        // ====================================
        // TOP PRODUCTS BY STOCK MOVEMENT (LAST 30 DAYS)
        // ====================================
        $topMovedProducts = StockTransaction::with('product')
            ->where('transaction_date', '>=', now()->subDays(30))
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // ====================================
        // CATEGORY DISTRIBUTION
        // ====================================
        $categoryDistribution = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])
            ->where('is_active', true)
            ->orderBy('products_count', 'desc')
            ->get();

        // ====================================
        // STOCK MOVEMENT CHART DATA (LAST 30 DAYS)
        // ====================================
        $stockMovementData = StockTransaction::selectRaw('
                DATE(transaction_date) as date,
                type,
                SUM(quantity) as total_quantity
            ')
            ->where('transaction_date', '>=', now()->subDays(30))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Format chart data
        $chartLabels = [];
        $stockInData = [];
        $stockOutData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('M d');

            $dayData = $stockMovementData->get($date, collect());
            $stockInData[] = $dayData->where('type', 'in')->sum('total_quantity');
            $stockOutData[] = $dayData->where('type', 'out')->sum('total_quantity');
        }

        // ====================================
        // EXPIRY MONITORING
        // ====================================
        // Get expired batches (stock transactions with expired dates)
        $expiredBatches = StockTransaction::expired()
            ->with(['product', 'supplier'])
            ->orderBy('expired_date', 'desc')
            ->limit(10)
            ->get();

        // Get near expiry batches
        $nearExpiryBatches = StockTransaction::nearExpiry()
            ->with(['product', 'supplier'])
            ->orderBy('expired_date', 'asc')
            ->limit(10)
            ->get();

        // ====================================
        // CRITICAL STOCK MONITORING
        // ====================================
        $criticalStockProducts = Product::with('category')
            ->where(function ($query) {
                $query->where('current_stock', '<=', 0)
                      ->orWhereRaw('current_stock <= minimum_stock');
            })
            ->orderBy('current_stock', 'asc')
            ->limit(10)
            ->get();

        // ====================================
        // LATEST STOCK IN WITH PRICE INFO
        // ====================================
     $latestStockInWithPrice = StockTransaction::stockIn()
    ->with(['product', 'supplier'])
    ->whereNotNull('total_price')  // <-- GANTI KE total_price
    ->orderBy('transaction_date', 'desc')
    ->limit(5)
    ->get();
        // ====================================
        // SUPPLIER PERFORMANCE (LAST 30 DAYS)
        // ====================================
        $supplierPerformance = StockTransaction::stockIn()
            ->with('supplier')
            ->where('transaction_date', '>=', now()->subDays(30))
            ->whereNotNull('supplier_id')
            ->selectRaw('
                supplier_id,
                COUNT(*) as total_transactions,
                SUM(quantity) as total_quantity
            ')
            ->groupBy('supplier_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            // Basic Statistics
            'totalProducts',
            'activeProducts',
            'totalCategories',
            'totalSuppliers',

            // Stock Status
            'lowStockProducts',
            'outOfStockProducts',
            'productsWithExpiredBatches',
            'productsWithNearExpiryBatches',

            // Stock Movement
            'recentTransactions',
            'stockInMovement',
            'stockOutMovement',

            // Alerts
            'stockAlerts',
            'alertCounts',

            // Analysis Data
            'topMovedProducts',
            'categoryDistribution',
            'criticalStockProducts',

            // Chart Data
            'chartLabels',
            'stockInData',
            'stockOutData',

            // Expiry Monitoring
            'expiredBatches',
            'nearExpiryBatches',

            // Additional Info
            'latestStockInWithPrice',
            'supplierPerformance'
        ));
    }
}
