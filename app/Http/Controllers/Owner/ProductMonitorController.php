<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductMonitorController extends Controller
{
    /**
     * Display product monitoring dashboard with filters
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->select('products.*')
            ->addSelect([
                'total_transactions' => StockTransaction::selectRaw('COUNT(*)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('created_at', '>=', now()->subDays(30)),
                'total_stock_in' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'in')
                    ->where('created_at', '>=', now()->subDays(30)),
                'total_stock_out' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_id', 'products.id')
                    ->where('type', 'out')
                    ->where('created_at', '>=', now()->subDays(30))
            ]);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->filled('stock_status')) {
            $status = $request->get('stock_status');
            switch ($status) {
                case 'low_stock':
                    $query->whereRaw('current_stock <= minimum_stock AND current_stock > 0');
                    break;
                case 'out_of_stock':
                    $query->where('current_stock', '<=', 0);
                    break;
                case 'normal':
                    $query->whereRaw('current_stock > minimum_stock');
                    break;
            }
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'stock_level':
                $query->orderBy('current_stock', $sortOrder);
                break;
            case 'inventory_value':
                $query->orderByRaw("(current_stock * purchase_price) {$sortOrder}");
                break;
            case 'activity':
                $query->orderBy('total_transactions', $sortOrder);
                break;
            case 'category':
                $query->join('categories', 'products.category_id', '=', 'categories.id')
                      ->orderBy('categories.name', $sortOrder);
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(15)->withQueryString();

        // Get filter options
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Statistics for header cards
        $stats = $this->getProductStats();

        return view('owner.product-monitor.index', compact(
            'products',
            'categories',
            'stats'
        ));
    }

    /**
     * Show detailed monitoring for specific product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'unit', 'stockAlerts' => function ($query) {
            $query->latest()->limit(10);
        }]);

        // Stock movement data for last 30 days
        $stockMovements = StockTransaction::where('product_id', $product->id)
            ->with(['user', 'supplier'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Stock trend data for chart (last 30 days)
        $stockTrend = StockTransaction::where('product_id', $product->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('MAX(stock_after) as stock_level')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly statistics
        $monthlyStats = [
            'stock_in' => StockTransaction::where('product_id', $product->id)
                ->where('type', 'in')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('quantity'),
            'stock_out' => StockTransaction::where('product_id', $product->id)
                ->where('type', 'out')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('quantity'),
            'total_value_in' => StockTransaction::where('product_id', $product->id)
                ->where('type', 'in')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('total_price'),
            'total_value_out' => StockTransaction::where('product_id', $product->id)
                ->where('type', 'out')
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('total_price'),
        ];

        return view('owner.product-monitor.show', compact(
            'product',
            'stockMovements',
            'stockTrend',
            'monthlyStats'
        ));
    }

    /**
     * Show products with low stock levels
     */
    public function lowStock(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->whereRaw('current_stock <= minimum_stock AND current_stock > 0')
            ->where('is_active', true);

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Sorting by urgency (lowest stock percentage first)
        $query->orderByRaw('(current_stock / GREATEST(minimum_stock, 1)) ASC')
              ->orderBy('current_stock', 'ASC');

        $lowStockProducts = $query->paginate(15)->withQueryString();

        // Add stock percentage for each product
        $lowStockProducts->getCollection()->transform(function ($product) {
            $product->stock_percentage = $product->minimum_stock > 0
                ? round(($product->current_stock / $product->minimum_stock) * 100, 1)
                : 0;
            return $product;
        });

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $totalLowStock = Product::whereRaw('current_stock <= minimum_stock AND current_stock > 0')
            ->where('is_active', true)
            ->count();

        return view('owner.product-monitor.low-stock', compact(
            'lowStockProducts',
            'categories',
            'totalLowStock'
        ));
    }

    /**
     * Show products that are out of stock
     */
    public function outOfStock(Request $request)
    {
        $query = Product::with(['category', 'unit'])
            ->where('current_stock', '<=', 0)
            ->where('is_active', true);

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Get last transaction date for each product
        $query->addSelect([
            'last_transaction_date' => StockTransaction::select('transaction_date')
                ->whereColumn('product_id', 'products.id')
                ->latest('transaction_date')
                ->limit(1)
        ]);

        $query->orderBy('last_transaction_date', 'desc');

        $outOfStockProducts = $query->paginate(15)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $totalOutOfStock = Product::where('current_stock', '<=', 0)
            ->where('is_active', true)
            ->count();

        // Days since last stock for each product
        $outOfStockProducts->getCollection()->transform(function ($product) {
            if ($product->last_transaction_date) {
                $product->days_out_of_stock = now()->diffInDays($product->last_transaction_date);
            } else {
                $product->days_out_of_stock = null;
            }
            return $product;
        });

        return view('owner.product-monitor.out-of-stock', compact(
            'outOfStockProducts',
            'categories',
            'totalOutOfStock'
        ));
    }

    /**
     * Show top performing products based on movement
     */
    public function topProducts(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $type = $request->get('type', 'movement'); // movement, revenue, quantity

        $query = Product::with(['category', 'unit']);

        switch ($type) {
            case 'revenue':
                $query->addSelect([
                    'total_revenue' => StockTransaction::selectRaw('COALESCE(SUM(total_price), 0)')
                        ->whereColumn('product_id', 'products.id')
                        ->where('type', 'out')
                        ->where('created_at', '>=', now()->subDays($period))
                ])->orderBy('total_revenue', 'desc');
                break;

            case 'quantity':
                $query->addSelect([
                    'total_quantity_out' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                        ->whereColumn('product_id', 'products.id')
                        ->where('type', 'out')
                        ->where('created_at', '>=', now()->subDays($period))
                ])->orderBy('total_quantity_out', 'desc');
                break;

            default: // movement
                $query->addSelect([
                    'total_transactions' => StockTransaction::selectRaw('COUNT(*)')
                        ->whereColumn('product_id', 'products.id')
                        ->where('created_at', '>=', now()->subDays($period))
                ])->orderBy('total_transactions', 'desc');
                break;
        }

        // Add revenue and quantity data for display
        $query->addSelect([
            'period_revenue' => StockTransaction::selectRaw('COALESCE(SUM(total_price), 0)')
                ->whereColumn('product_id', 'products.id')
                ->where('type', 'out')
                ->where('created_at', '>=', now()->subDays($period)),
            'period_quantity_out' => StockTransaction::selectRaw('COALESCE(SUM(quantity), 0)')
                ->whereColumn('product_id', 'products.id')
                ->where('type', 'out')
                ->where('created_at', '>=', now()->subDays($period)),
            'period_transactions' => StockTransaction::selectRaw('COUNT(*)')
                ->whereColumn('product_id', 'products.id')
                ->where('created_at', '>=', now()->subDays($period))
        ]);

        // Apply category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        $topProducts = $query->where('is_active', true)
            ->having('period_transactions', '>', 0)
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('owner.product-monitor.top-products', compact(
            'topProducts',
            'categories',
            'period',
            'type'
        ));
    }

    /**
     * Get product statistics for dashboard cards
     */
    private function getProductStats()
    {
        return [
            'total_products' => Product::where('is_active', true)->count(),
            'low_stock_count' => Product::whereRaw('current_stock <= minimum_stock AND current_stock > 0')
                ->where('is_active', true)->count(),
            'out_of_stock_count' => Product::where('current_stock', '<=', 0)
                ->where('is_active', true)->count(),
            'total_inventory_value' => Product::where('is_active', true)
                ->selectRaw('SUM(current_stock * purchase_price) as total')
                ->value('total') ?? 0,
            'active_products_percent' => Product::where('is_active', true)->count() > 0
                ? round((Product::where('is_active', true)->count() / Product::count()) * 100, 1)
                : 0,
        ];
    }
}
