<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockAlert;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockAlertController extends Controller
{
    /**
     * Display listing of stock alerts
     */
    public function index(Request $request): View
    {
        $query = StockAlert::with(['product.category', 'product.unit'])
                          ->latest('alert_date');

        // Filter by alert type
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

        // Search by product name or alert message
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%")
                                  ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        $alerts = $query->paginate(15)->withQueryString();

        // Get alert statistics
        $stats = [
            'total' => StockAlert::count(),
            'unread' => StockAlert::unread()->count(),
            'minimum_stock' => StockAlert::minimumStock()->count(),
            'out_of_stock' => StockAlert::outOfStock()->count(),
        ];

        return view('admin.stock-alerts.index', compact('alerts', 'stats'));
    }

    /**
     * Show the specified stock alert
     */
    public function show(StockAlert $stockAlert): View
    {
        $stockAlert->load(['product.category', 'product.unit', 'product.stockTransactions' => function ($query) {
            $query->latest()->limit(10);
        }]);

        // Mark as read when viewed
        if (!$stockAlert->is_read) {
            $stockAlert->update(['is_read' => true]);
        }

        return view('admin.stock-alerts.show', compact('stockAlert'));
    }

    /**
     * Mark specific alert as read
     */
    public function markAsRead(StockAlert $stockAlert): JsonResponse
    {
        $stockAlert->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Alert berhasil ditandai sebagai sudah dibaca.'
        ]);
    }

    /**
     * Mark all alerts as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $updated = StockAlert::unread()->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menandai {$updated} alert sebagai sudah dibaca.",
            'updated_count' => $updated
        ]);
    }

    /**
     * Mark multiple alerts as read
     */
    public function markMultipleAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'alert_ids' => 'required|array',
            'alert_ids.*' => 'exists:stock_alerts,id'
        ]);

        $updated = StockAlert::whereIn('id', $request->alert_ids)
                            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menandai {$updated} alert sebagai sudah dibaca.",
            'updated_count' => $updated
        ]);
    }

    /**
     * Delete the specified stock alert
     */
    public function destroy(StockAlert $stockAlert): RedirectResponse
    {
        $productName = $stockAlert->product->name;
        $stockAlert->delete();

        return redirect()->route('admin.stock-alerts.index')
                        ->with('success', "Alert untuk produk '{$productName}' berhasil dihapus.");
    }

    /**
     * Bulk delete alerts
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'alert_ids' => 'required|array',
            'alert_ids.*' => 'exists:stock_alerts,id'
        ]);

        $deleted = StockAlert::whereIn('id', $request->alert_ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "Berhasil menghapus {$deleted} alert.",
            'deleted_count' => $deleted
        ]);
    }

    /**
     * Get unread alerts count for notifications
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = StockAlert::unread()->count();

        return response()->json([
            'count' => $count,
            'has_alerts' => $count > 0
        ]);
    }

    /**
     * Get recent alerts for dashboard notifications
     */
    public function getRecentAlerts(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);

        $alerts = StockAlert::with(['product'])
                           ->unread()
                           ->latest('alert_date')
                           ->limit($limit)
                           ->get()
                           ->map(function ($alert) {
                               return [
                                   'id' => $alert->id,
                                   'message' => $alert->message,
                                   'type' => $alert->type,
                                   'product_name' => $alert->product->name,
                                   'product_code' => $alert->product->code,
                                   'alert_date' => $alert->alert_date->diffForHumans(),
                                   'url' => route('admin.stock-alerts.show', $alert)
                               ];
                           });

        return response()->json([
            'alerts' => $alerts,
            'total_unread' => StockAlert::unread()->count()
        ]);
    }

    /**
     * Create new stock alert (usually called from system)
     */
    public function createAlert(Product $product, string $type): StockAlert
    {
        // Avoid duplicate alerts for the same product and type
        $existingAlert = StockAlert::where('product_id', $product->id)
                                  ->where('type', $type)
                                  ->where('is_read', false)
                                  ->first();

        if ($existingAlert) {
            return $existingAlert;
        }

        $message = $this->generateAlertMessage($product, $type);

        return StockAlert::create([
            'product_id' => $product->id,
            'type' => $type,
            'message' => $message,
            'alert_date' => now(),
            'is_read' => false
        ]);
    }

    /**
     * Generate alert message based on type
     */
    private function generateAlertMessage(Product $product, string $type): string
    {
        switch ($type) {
            case StockAlert::TYPES['MINIMUM_STOCK']:
                return "Stok produk '{$product->name}' ({$product->code}) telah mencapai batas minimum. " .
                       "Stok saat ini: {$product->current_stock} {$product->unit->symbol}, " .
                       "Minimum: {$product->minimum_stock} {$product->unit->symbol}";

            case StockAlert::TYPES['OUT_OF_STOCK']:
                return "Produk '{$product->name}' ({$product->code}) telah habis! " .
                       "Stok saat ini: {$product->current_stock} {$product->unit->symbol}";

            default:
                return "Alert untuk produk '{$product->name}' ({$product->code})";
        }
    }

    /**
     * Clear all read alerts
     */
    public function clearReadAlerts(): RedirectResponse
    {
        $deleted = StockAlert::where('is_read', true)->delete();

        return redirect()->route('admin.stock-alerts.index')
                        ->with('success', "Berhasil menghapus {$deleted} alert yang sudah dibaca.");
    }
}
