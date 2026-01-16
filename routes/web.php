<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StockTransactionController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Owner\ProductMonitorController;
use App\Http\Controllers\ThemeController;

Route::get('/', HomeController::class)->name('home');
Route::middleware('guest')->group(function () {
    Route::view('/register', 'register')->name('register');
    Route::post('/register', [AuthController::class, 'store']);

    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});
Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

Route::middleware('auth')->group(function () {

      // Global Search Routes
    Route::get('/admin/search', [App\Http\Controllers\GlobalSearchController::class, 'search'])
        ->name('admin.search');

    // Notification Routes
    Route::prefix('admin/notifications')->name('admin.notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])
            ->name('index');
        Route::get('/count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])
            ->name('count');
        Route::get('/latest', [App\Http\Controllers\NotificationController::class, 'getLatest'])
            ->name('latest');
        Route::post('/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])
            ->name('read');
    });
      Route::prefix('admin/alerts')->name('admin.alerts.')->group(function () {
        Route::get('/', function() {
            $alerts = \App\Models\StockAlert::with('product')
                ->orderBy('alert_date', 'desc')
                ->paginate(20);
            return view('admin.alerts.index', compact('alerts'));
        })->name('index');
    });

    // AUTH
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // PROFILE
    Route::prefix('profile')->name('admin.profile.')->group(function () {
        Route::get('/', [ProfilController::class, 'show'])->name('show');
        Route::get('/edit', [ProfilController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::get('/password/edit', [ProfilController::class, 'editPassword'])->name('password.edit');
        Route::put('/password/update', [ProfilController::class, 'updatePassword'])->name('password.update');
    });

    // ADMIN
    Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {


        // UNITS
        Route::patch('units/{unit}/toggle', [UnitController::class, 'toggle'])->name('units.toggle');
        Route::post('units/bulk-delete', [UnitController::class, 'bulkDelete'])->name('units.bulk-delete');
        Route::post('units/bulk-toggle', [UnitController::class, 'bulkToggle'])->name('units.bulk-toggle');
        Route::resource('units', UnitController::class)->names('units');

        // CATEGORIES
        Route::patch('categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');
        Route::resource('categories', CategoryController::class)->names('categories');

        // SUPPLIERS
        Route::patch('suppliers/{supplier}/toggle', [SupplierController::class, 'toggle'])->name('suppliers.toggle');
        Route::post('suppliers/bulk-action', [SupplierController::class, 'bulkAction'])->name('suppliers.bulk-action');
        Route::resource('suppliers', SupplierController::class)->names('suppliers');

        // PRODUCTS
        Route::patch('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
        Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
        Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
        Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
        Route::get('products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
        Route::resource('products', ProductController::class)->names('products');

        // STOCK TRANSACTIONS
        Route::get('transactions', [StockTransactionController::class, 'index'])->name('stock-transactions.index');
        Route::get('stock-transactions/stock-in', [StockTransactionController::class, 'stockIn'])->name('stock-transactions.stock-in');
        Route::post('stock-transactions/stock-in', [StockTransactionController::class, 'storeStockIn'])->name('stock-transactions.store-stock-in');
        Route::get('stock-transactions/stock-out', [StockTransactionController::class, 'stockOut'])->name('stock-transactions.stock-out');
        Route::post('stock-transactions/stock-out', [StockTransactionController::class, 'storeStockOut'])->name('stock-transactions.store-stock-out');
        Route::get('stock-transactions/{stockTransaction}', [StockTransactionController::class, 'show'])->name('stock-transactions.show');
        Route::delete('stock-transactions/{stockTransaction}', [StockTransactionController::class, 'destroy'])->name('stock-transactions.destroy');

        // REPORTS
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/stock-pdf', [ReportController::class, 'stockPdf'])->name('stock.pdf');
        });

        // API
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('products/{product}/stock', [ProductController::class, 'getProductStock'])->name('products.stock');
        });
    });

    // OWNER ROUTES
    Route::prefix('admin')->middleware('superuser')->name('admin.')->group(function () {
        // REPORTS
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/stock-pdf', [ReportController::class, 'stockPdf'])->name('stock.pdf');
        });

        Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('dashboard');



        // // DASHBOARD - Executive Dashboard
        // Route::get('/dashboard', [\App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');

        // // DASHBOARD API ENDPOINTS
        // Route::prefix('api')->name('api.')->group(function () {
        //     Route::get('/kpi-data', [DashboardController::class, 'getKPIDataAPI'])->name('kpi-data');
        //     Route::get('/chart-data', [DashboardController::class, 'getChartDataAPI'])->name('chart-data');
        //     Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
        //     Route::get('/quick-stats', [DashboardController::class, 'getQuickStats'])->name('quick-stats');
        //     Route::get('/business-metrics', [DashboardController::class, 'getBusinessMetrics'])->name('business-metrics');
        // });

        // // PRODUCT MONITORING - Read Only
        // Route::prefix('products')->name('products.')->group(function () {
        //     Route::get('/', [ProductMonitorController::class, 'index'])->name('index');
        //     Route::get('/show/{product}', [ProductMonitorController::class, 'show'])->name('show');
        //     Route::get('/low-stock', [ProductMonitorController::class, 'lowStock'])->name('low-stock');
        //     Route::get('/out-of-stock', [ProductMonitorController::class, 'outOfStock'])->name('out-of-stock');
        //     Route::get('/top-products', [ProductMonitorController::class, 'topProducts'])->name('top-products');
        //     Route::get('/performance', [ProductMonitorController::class, 'performance'])->name('performance');
        // });
    });
});
