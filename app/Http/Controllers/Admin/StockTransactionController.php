<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockAlert;
use Illuminate\Http\Request;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of stock transactions with filters
     */
    public function index(Request $request)
    {
        $query = StockTransaction::with(['product', 'supplier', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")

                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Data for filters
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'code']);
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('admin.stock-transactions.index', compact('transactions', 'products', 'suppliers'));
    }

    /**
     * Show form for stock in (barang masuk)
     */
    public function stockIn()
    {
        $products = Product::active()->with(['category'])->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('admin.stock-transactions.stock-in', compact('products', 'suppliers'));
    }

    /**
     * Store stock in transaction
     */

    public function storeStockIn(Request $request)
    {
        // Log awal: data yang diterima
        Log::info('Store Stock In - Incoming request', $request->all());

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'expired_date' => 'nullable|date|after:today',

            'transaction_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ], [
            // Custom messages...
        ]);

        if ($validator->fails()) {
            Log::warning('Store Stock In - Validation failed', [
                'errors' => $validator->errors()->all()
            ]);

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            $totalPrice = $request->total_price;
            $stockBefore = $product->current_stock;
            $stockAfter = $stockBefore + $quantity;

            Log::info('Store Stock In - Creating stock transaction', [
                'product_id' => $product->id,
                'stock_before' => $stockBefore,
                'quantity' => $quantity,
                'stock_after' => $stockAfter
            ]);

            $transaction = StockTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'type' => StockTransaction::TYPES['IN'],
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'expired_date' => $request->expired_date,

                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $request->notes,
                'transaction_date' => $request->transaction_date,
            ]);

            $product->update([
                'current_stock' => $stockAfter,
            ]);

            StockAlert::where('product_id', $product->id)
                ->whereIn('type', [StockAlert::TYPES['MINIMUM_STOCK'], StockAlert::TYPES['OUT_OF_STOCK']])
                ->where('is_read', false)
                ->delete();

            if ($request->expired_date) {
                Log::info('Store Stock In - Creating expiry alert');
                $this->createExpiryAlerts($product, $transaction);
            }

            DB::commit();

            $message = "Stok barang {$product->name} berhasil ditambahkan. Stok sekarang: {$stockAfter}";


            Log::info('Store Stock In - Success', ['message' => $message]);

            return redirect()->route('admin.stock-transactions.index')
                ->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Store Stock In - Exception caught', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi.')
                ->withInput();
        }
    }


    /**
     * Show form for stock out (barang keluar)
     */
    public function stockOut()
    {
        $products = Product::active()
            ->where('current_stock', '>', 0)
            ->with(['category'])
            ->orderBy('name')
            ->get();

        return view('admin.stock-transactions.stock-out', compact('products'));
    }

    /**
     * Store stock out transaction
     * Note: No price information needed for stock out as per revision
     */
    public function storeStockOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ], [
            'product_id.required' => 'Produk harus dipilih.',
            'product_id.exists' => 'Produk tidak valid.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'transaction_date.required' => 'Tanggal transaksi harus diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',
            'transaction_date.before_or_equal' => 'Tanggal transaksi tidak boleh lebih dari hari ini.',
            'notes.max' => 'Catatan maksimal 500 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            $stockBefore = $product->current_stock;

            // Validate sufficient stock
            if ($quantity > $stockBefore) {
                return redirect()->back()
                    ->with('error', "Stok tidak mencukupi. Stok tersedia: {$stockBefore}")
                    ->withInput();
            }

            $stockAfter = $stockBefore - $quantity;

            // Create stock transaction (no price info for stock out)
            $transaction = StockTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => null, // Stock out doesn't need supplier
                'user_id' => auth()->id(),
                'type' => StockTransaction::TYPES['OUT'],
                'quantity' => $quantity,
                'total_price' => null, // No price for stock out
                'expired_date' => null, // No expiry date for stock out

                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'notes' => $request->notes,
                'transaction_date' => $request->transaction_date,
            ]);

            // Update product stock
            $product->update([
                'current_stock' => $stockAfter,
            ]);

            // Create stock alerts if needed
            $this->createStockAlerts($product);

            DB::commit();

            $message = "Stok barang {$product->name} berhasil dikurangi. Stok sekarang: {$stockAfter}";

            if ($stockAfter <= 0) {
                $message .= " - PERINGATAN: Stok habis!";
            } elseif ($stockAfter <= $product->minimum_stock) {
                $message .= " - PERINGATAN: Stok rendah!";
            }

            return redirect()->route('admin.stock-transactions.index')
                ->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi.')
                ->withInput();
        }
    }

    /**
     * Display the specified transaction
     */
    public function show(StockTransaction $stockTransaction)
    {
        $stockTransaction->load(['product.category', 'supplier', 'user']);

        return view('admin.stock-transactions.show', compact('stockTransaction'));
    }

    /**
     * Remove the specified transaction (with validation)
     */
    public function destroy(StockTransaction $stockTransaction)
    {
        try {
            DB::beginTransaction();

            // Validate: Can only delete latest transaction for a product
            $latestTransaction = StockTransaction::where('product_id', $stockTransaction->product_id)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestTransaction->id !== $stockTransaction->id) {
                return redirect()->back()
                    ->with('error', 'Hanya transaksi terakhir yang dapat dihapus untuk menjaga konsistensi stok.');
            }

            $product = $stockTransaction->product;

            // Revert stock changes
            $product->update([
                'current_stock' => $stockTransaction->stock_before
            ]);

            // Delete the transaction
            $stockTransaction->delete();

            // Update stock alerts
            $this->createStockAlerts($product);

            // Update expiry alerts if the deleted transaction had expiry date
            if ($stockTransaction->expired_date) {
                $this->updateExpiryAlerts($product);
            }

            DB::commit();

            return redirect()->route('admin.stock-transactions.index')
                ->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus transaksi.');
        }
    }

    /**
     * Create stock alerts based on stock level
     */
    private function createStockAlerts(Product $product)
    {
        // Clear existing unread stock alerts for this product
        StockAlert::where('product_id', $product->id)
            ->whereIn('type', [StockAlert::TYPES['MINIMUM_STOCK'], StockAlert::TYPES['OUT_OF_STOCK']])
            ->where('is_read', false)
            ->delete();

        // Create new alerts based on current stock
        if ($product->current_stock <= 0) {
            StockAlert::create([
                'product_id' => $product->id,
                'type' => StockAlert::TYPES['OUT_OF_STOCK'],
                'message' => "Stok produk {$product->name} telah habis!",
                'alert_date' => now(),
            ]);
        } elseif ($product->current_stock <= $product->minimum_stock) {
            StockAlert::create([
                'product_id' => $product->id,
                'type' => StockAlert::TYPES['MINIMUM_STOCK'],
                'message' => "Stok produk {$product->name} sudah mencapai batas minimum ({$product->minimum_stock})! Stok saat ini: {$product->current_stock}",
                'alert_date' => now(),
            ]);
        }
    }

    /**
     * Create expiry alerts based on expiry date from stock transaction
     */
    private function createExpiryAlerts(Product $product, StockTransaction $transaction)
    {
        if (!$transaction->expired_date) {
            return;
        }

        $expiredDate = $transaction->expired_date;
        $daysUntilExpiry = $expiredDate->diffInDays(now());

        // Create alert if already expired
        if ($expiredDate->isPast()) {
            StockAlert::create([
                'product_id' => $product->id,
                'type' => StockAlert::TYPES['EXPIRED'],
                'message' => "Produk {$product->name} sudah expired pada {$expiredDate->format('d/m/Y')}!",
                'alert_date' => now(),
            ]);
        }
        // Create alert if near expiry (within 7 days)
        elseif ($daysUntilExpiry <= 7) {
            StockAlert::create([
                'product_id' => $product->id,
                'type' => StockAlert::TYPES['NEAR_EXPIRY'],
                'message' => "Produk {$product->name} akan expired dalam {$daysUntilExpiry} hari ({$expiredDate->format('d/m/Y')})!",

                'alert_date' => now(),
            ]);
        }
    }

    /**
     * Update expiry alerts for a product (called when deleting transactions)
     */
    private function updateExpiryAlerts(Product $product)
    {
        // Clear existing expiry alerts for this product
        StockAlert::where('product_id', $product->id)
            ->whereIn('type', [StockAlert::TYPES['EXPIRED'], StockAlert::TYPES['NEAR_EXPIRY']])
            ->where('is_read', false)
            ->delete();

        // Get all stock in transactions with expiry dates for this product
        $stockInTransactions = StockTransaction::where('product_id', $product->id)
            ->where('type', StockTransaction::TYPES['IN'])
            ->whereNotNull('expired_date')
            ->get();

        // Create alerts for each expired or near expiry batch
        foreach ($stockInTransactions as $transaction) {
            $this->createExpiryAlerts($product, $transaction);
        }
    }

    /**
     * Get expired batches for a product
     */
    public function getExpiredBatches(Product $product)
    {
        return StockTransaction::where('product_id', $product->id)
            ->where('type', StockTransaction::TYPES['IN'])
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', now())
            ->orderBy('expired_date', 'desc')
            ->get();
    }

    /**
     * Get near expiry batches for a product
     */
    public function getNearExpiryBatches(Product $product)
    {
        return StockTransaction::where('product_id', $product->id)
            ->where('type', StockTransaction::TYPES['IN'])
            ->whereNotNull('expired_date')
            ->whereBetween('expired_date', [now(), now()->addDays(7)])
            ->orderBy('expired_date', 'asc')
            ->get();
    }
}
