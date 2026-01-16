<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StockAlert;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
class AlertMonitorController extends Controller
{
    /**
     * Display all active alerts
     */
    public function index(Request $request): View
    {
        $query = StockAlert::with(['product.category', 'product.unit'])
            ->latest('alert_date');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('alert_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('alert_date', '<=', $request->date_to);
        }

        $alerts = $query->paginate(20);

        $stats = [
            'total_alerts' => StockAlert::count(),
            'unread_alerts' => StockAlert::unread()->count(),
            'critical_alerts' => StockAlert::where('type', StockAlert::TYPES['OUT_OF_STOCK'])->unread()->count(),
            'minimum_stock_alerts' => StockAlert::where('type', StockAlert::TYPES['MINIMUM_STOCK'])->unread()->count(),
        ];

        return view('owner.alert-monitor.index', compact('alerts', 'stats'));
    }

    /**
     * Display critical alerts that need immediate attention
     */
    public function critical(): View
    {
        $criticalAlerts = StockAlert::with(['product.category', 'product.unit'])
            ->where('type', StockAlert::TYPES['OUT_OF_STOCK'])
            ->orWhere(function($query) {
                $query->where('type', StockAlert::TYPES['MINIMUM_STOCK'])
                      ->whereHas('product', function($q) {
                          $q->whereRaw('current_stock <= minimum_stock * 0.5'); // Very low stock
                      });
            })
            ->unread()
            ->latest('alert_date')
            ->get();

        $criticalProducts = Product::with(['category', 'unit'])
            ->where('current_stock', '<=', 0)
            ->orWhereRaw('current_stock <= minimum_stock * 0.5')
            ->get();

        return view('owner.alert-monitor.critical', compact('criticalAlerts', 'criticalProducts'));
    }

    /**
     * Display alert summary by category
     */
    public function summary(): View
    {
        $alertSummary = StockAlert::selectRaw('
                type,
                COUNT(*) as total_alerts,
                COUNT(CASE WHEN is_read = 0 THEN 1 END) as unread_alerts
            ')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $categoryAlerts = StockAlert::join('products', 'stock_alerts.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('
                categories.name as category_name,
                categories.id as category_id,
                COUNT(*) as total_alerts,
                COUNT(CASE WHEN stock_alerts.is_read = 0 THEN 1 END) as unread_alerts,
                COUNT(CASE WHEN stock_alerts.type = ? THEN 1 END) as out_of_stock_alerts,
                COUNT(CASE WHEN stock_alerts.type = ? THEN 1 END) as minimum_stock_alerts
            ', [StockAlert::TYPES['OUT_OF_STOCK'], StockAlert::TYPES['MINIMUM_STOCK']])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('unread_alerts')
            ->get();

        $recentTrends = StockAlert::selectRaw('
                DATE(alert_date) as alert_day,
                COUNT(*) as alerts_count,
                COUNT(CASE WHEN type = ? THEN 1 END) as out_of_stock_count,
                COUNT(CASE WHEN type = ? THEN 1 END) as minimum_stock_count
            ', [StockAlert::TYPES['OUT_OF_STOCK'], StockAlert::TYPES['MINIMUM_STOCK']])
            ->where('alert_date', '>=', now()->subDays(7))
            ->groupBy('alert_day')
            ->orderBy('alert_day')
            ->get();

        return view('owner.alert-monitor.summary', compact(
            'alertSummary',
            'categoryAlerts',
            'recentTrends'
        ));
    }
}
