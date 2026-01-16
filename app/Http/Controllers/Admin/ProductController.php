<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockAlert;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of products with advanced search and filters
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category'])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('code', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->category_id, function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->when($request->unit, function ($q) use ($request) {
                $q->where('unit', $request->unit);
            })
            ->when($request->stock_status, function ($q) use ($request) {
                if ($request->stock_status === 'low_stock') {
                    $q->lowStock();
                } elseif ($request->stock_status === 'out_of_stock') {
                    $q->outOfStock();
                } elseif ($request->stock_status === 'normal') {
                    $q->whereRaw('current_stock > minimum_stock');
                }
            })
            ->when($request->expiry_status, function ($q) use ($request) {
                if ($request->expiry_status === 'expired') {
                    $q->hasExpiredBatches();
                } elseif ($request->expiry_status === 'near_expiry') {
                    $q->hasNearExpiryBatches();
                }
            })
            ->when($request->is_active !== null, function ($q) use ($request) {
                $q->where('is_active', $request->is_active);
            })
            ->when($request->sort_by, function ($q) use ($request) {
                $direction = $request->sort_direction === 'desc' ? 'desc' : 'asc';
                $q->orderBy($request->sort_by, $direction);
            }, function ($q) {
                $q->latest();
            });

        $products = $query->paginate(15)->withQueryString();

        // Data untuk filter dropdown
        $categories = Category::active()->orderBy('name')->get();
        $units = Product::UNITS;

        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'low_stock_count' => Product::lowStock()->count(),
            'out_of_stock_count' => Product::outOfStock()->count(),
            'expired_count' => Product::hasExpiredBatches()->count(),
            'near_expiry_count' => Product::hasNearExpiryBatches()->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'units', 'stats'));
    }


    /**
     * Show the form for creating a new product
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $units = Product::UNITS;

        return view('admin.products.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created product in storage
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:products,code',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|in:' . implode(',', array_values(Product::UNITS)),
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',

            'is_active' => 'boolean',
        ]);

        DB::beginTransaction();
        try {


            // Auto-generate code if not provided
            if (empty($validated['code'])) {
                $validated['code'] = $this->generateProductCode($validated['category_id']);
            }

            $product = Product::create($validated);

            // Create stock alert if necessary
            $this->checkAndCreateStockAlert($product);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (Exception $e) {
            DB::rollback();



            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified product with stock history and batch information
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'stockTransactions.user', 'stockAlerts']);

        // Recent stock transactions
        $recentTransactions = $product->stockTransactions()
            ->with(['user', 'supplier'])
            ->latest()
            ->take(10)
            ->get();

        // Stock movement chart data (last 30 days)
        $stockMovements = $product->stockTransactions()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($transactions) {
                return [
                    'stock_in' => $transactions->where('type', 'in')->sum('quantity'),
                    'stock_out' => $transactions->where('type', 'out')->sum('quantity'),
                ];
            });

        // Batch information for stock IN transactions
        // $stockInBatches = $product->stockTransactions()
        //     ->stockIn()
        //     ->whereNotNull('expired_date')
        //     ->orderBy('expired_date', 'asc')
        //     ->get()
        //     ->groupBy('batch_number');

        // Expired batches
        $expiredBatches = $product->expiredBatches;

        // Near expiry batches
        $nearExpiryBatches = $product->nearExpiryBatches;

        return view('admin.products.show', compact(
            'product',
            'recentTransactions',
            'stockMovements',
            'stockInBatches',
            'expiredBatches',
            'nearExpiryBatches'
        ));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $units = Product::UNITS; // Menggunakan enum dari model

        return view('admin.products.edit', compact('product', 'categories', 'units'));
    }

    /**
     * Update the specified product in storage
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:products,code,' . $product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|in:' . implode(',', array_values(Product::UNITS)),
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',

            'is_active' => 'boolean',
        ]);

        // Jika code kosong, pakai code lama
        if (empty($validated['code'])) {
            $validated['code'] = $product->code;
        }

        DB::beginTransaction();
        try {

            $product->update($validated);

            // Check and create stock alert if necessary
            $this->checkAndCreateStockAlert($product);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diupdate!');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate produk: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy(Product $product): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // Check if product has stock transactions
            if ($product->stockTransactions()->exists()) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus produk yang memiliki riwayat transaksi!');
            }


            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product active status
     */
    public function toggle(Product $product): RedirectResponse
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Produk berhasil {$status}!");
    }

    /**
     * Generate unique product code
     */
    private function generateProductCode(int $categoryId): string
    {
        $category = Category::find($categoryId);
        $prefix = $category ? strtoupper(substr($category->code, 0, 3)) : 'PRD';

        $lastProduct = Product::where('code', 'like', $prefix . '%')
            ->latest('id')
            ->first();

        if ($lastProduct) {
            $lastNumber = intval(substr($lastProduct->code, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . sprintf('%04d', $newNumber);
    }

    /**
     * Check and create stock alert if necessary
     */
    private function checkAndCreateStockAlert(Product $product): void
    {
        // Remove existing alerts for this product
        $product->stockAlerts()->delete();

        // Check stock alerts
        if ($product->current_stock <= 0) {
            $product->stockAlerts()->create([
                'type' => StockAlert::TYPES['OUT_OF_STOCK'],
                'message' => "Produk {$product->name} telah habis!",
                'alert_date' => now(),
            ]);
        } elseif ($product->current_stock <= $product->minimum_stock) {
            $product->stockAlerts()->create([
                'type' => StockAlert::TYPES['MINIMUM_STOCK'],
                'message' => "Stok produk {$product->name} sudah mencapai batas minimum ({$product->current_stock} {$product->unit_text})!",
                'alert_date' => now(),
            ]);
        }

        // Check expiry alerts
        if ($product->expired_date) {
            if ($product->is_expired) {
                $product->stockAlerts()->create([
                    'type' => StockAlert::TYPES['EXPIRED'],
                    'message' => "Produk {$product->name} sudah expired pada {$product->expired_date->format('d/m/Y')}!",
                    'alert_date' => now(),
                ]);
            } elseif ($product->is_near_expiry) {
                $daysLeft = $product->expired_date->diffInDays(now());
                $product->stockAlerts()->create([
                    'type' => StockAlert::TYPES['NEAR_EXPIRY'],
                    'message' => "Produk {$product->name} akan expired dalam {$daysLeft} hari ({$product->expired_date->format('d/m/Y')})!",
                    'alert_date' => now(),
                ]);
            }
        }
    }
}
