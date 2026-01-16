<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAlert;

class NotificationController extends Controller
{
    public function index()
    {
        $alerts = StockAlert::with('product')
            ->orderBy('alert_date', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'alerts' => $alerts
        ]);
    }

    public function getUnreadCount()
    {
        $count = StockAlert::unread()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    public function getLatest()
    {
        $alerts = StockAlert::with('product')
            ->unread()
            ->orderBy('alert_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'message' => $alert->message,
                    'product_name' => $alert->product->name,
                    'date' => $alert->alert_date->format('d M Y'),
                    'time' => $alert->alert_date->format('H:i'),
                    'icon' => $this->getAlertIcon($alert->type),
                    'color' => $this->getAlertColor($alert->type),
                    'url' => route('admin.products.show', $alert->product_id)
                ];
            });

        return response()->json([
            'success' => true,
            'alerts' => $alerts
        ]);
    }

    public function markAsRead(Request $request)
    {
        $alertId = $request->get('alert_id');

        if ($alertId) {
            StockAlert::find($alertId)->update(['is_read' => true]);
        } else {
            // Mark all as read
            StockAlert::unread()->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai sudah dibaca'
        ]);
    }

    private function getAlertIcon($type)
    {
        switch ($type) {
            case 'out_of_stock':
                return 'fas fa-exclamation-triangle';
            case 'minimum_stock':
                return 'fas fa-exclamation-circle';
            case 'expired':
                return 'fas fa-calendar-times';
            case 'near_expiry':
                return 'fas fa-clock';
            default:
                return 'fas fa-bell';
        }
    }

    private function getAlertColor($type)
    {
        switch ($type) {
            case 'out_of_stock':
                return 'danger';
            case 'minimum_stock':
                return 'warning';
            case 'expired':
                return 'danger';
            case 'near_expiry':
                return 'warning';
            default:
                return 'info';
        }
    }
}
