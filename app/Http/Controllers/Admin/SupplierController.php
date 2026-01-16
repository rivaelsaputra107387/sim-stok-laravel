<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers with search and filter functionality
     */
    public function index(Request $request): View
    {
        $query = Supplier::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowedSorts = ['name', 'code', 'email', 'phone', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $suppliers = $query->withCount('stockTransactions')
                          ->paginate(10)
                          ->appends($request->query());

        return view('admin.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier
     */
    public function create(): View
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage
     */
    public function store(Request $request): RedirectResponse
    {
        // PERBAIKAN: Ubah validasi code menjadi nullable
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:suppliers,code', // Ubah dari required ke nullable
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'code.unique' => 'Kode supplier sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            // Auto-generate code if not provided
            if (empty($validated['code'])) {
                $validated['code'] = $this->generateSupplierCode($validated['name']);
            }

            $supplier = Supplier::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.suppliers.index')
                ->with('success', 'Supplier berhasil ditambahkan.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating supplier: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan supplier.');
        }
    }

    /**
     * Display the specified supplier with transaction history
     */
    public function show(Supplier $supplier): View
    {
        // Load relationships
        $supplier->load(['stockTransactions' => function($query) {
            $query->with(['product', 'user'])
                  ->latest()
                  ->take(10);
        }]);

        // Get statistics
        $stats = [
            'total_transactions' => $supplier->stockTransactions()->count(),
            'total_value' => $supplier->stockTransactions()->sum('total_price'),
            'last_transaction' => $supplier->stockTransactions()->latest()->first()?->transaction_date,
            'active_products' => $supplier->stockTransactions()
                                         ->distinct('product_id')
                                         ->count('product_id')
        ];

        return view('admin.suppliers.show', compact('supplier', 'stats'));
    }

    /**
     * Show the form for editing the specified supplier
     */
    public function edit(Supplier $supplier): View
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('suppliers', 'code')->ignore($supplier->id)
            ],
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama supplier wajib diisi.',
            'code.required' => 'Kode supplier wajib diisi.',
            'code.unique' => 'Kode supplier sudah digunakan.',
            'email.email' => 'Format email tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $supplier->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.suppliers.index')
                ->with('success', 'Supplier berhasil diperbarui.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating supplier: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui supplier.');
        }
    }

    /**
     * Remove the specified supplier from storage
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        try {
            // Check if supplier has related transactions
            $transactionCount = $supplier->stockTransactions()->count();

            if ($transactionCount > 0) {
                return back()->with('error',
                    "Supplier tidak dapat dihapus karena memiliki {$transactionCount} transaksi terkait."
                );
            }

            DB::beginTransaction();

            $supplierName = $supplier->name;
            $supplier->delete();

            DB::commit();

            return redirect()
                ->route('admin.suppliers.index')
                ->with('success', "Supplier '{$supplierName}' berhasil dihapus.");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting supplier: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus supplier.');
        }
    }

    /**
     * Toggle supplier active status
     */
    public function toggle(Supplier $supplier): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $supplier->update([
                'is_active' => !$supplier->is_active
            ]);

            DB::commit();

            $status = $supplier->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return back()->with('success', "Supplier berhasil {$status}.");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error toggling supplier status: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan saat mengubah status supplier.');
        }
    }

    /**
     * Get supplier data for API/AJAX requests
     */
    public function getSupplierData(Request $request): JsonResponse
    {
        try {
            $query = Supplier::where('is_active', true);

            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            }

            $suppliers = $query->select('id', 'name', 'code', 'phone', 'email')
                              ->orderBy('name')
                              ->get();

            return response()->json([
                'success' => true,
                'data' => $suppliers
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching supplier data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data supplier.'
            ], 500);
        }
    }

    /**
     * Bulk actions for suppliers
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'supplier_ids' => 'required|array|min:1',
            'supplier_ids.*' => 'exists:suppliers,id'
        ]);

        try {
            DB::beginTransaction();

            $suppliers = Supplier::whereIn('id', $request->supplier_ids);
            $count = $suppliers->count();

            switch ($request->action) {
                case 'activate':
                    $suppliers->update(['is_active' => true]);
                    $message = "{$count} supplier berhasil diaktifkan.";
                    break;

                case 'deactivate':
                    $suppliers->update(['is_active' => false]);
                    $message = "{$count} supplier berhasil dinonaktifkan.";
                    break;

                case 'delete':
                    // Check for related transactions
                    $supplierIds = $request->supplier_ids;
                    $hasTransactions = DB::table('stock_transactions')
                        ->whereIn('supplier_id', $supplierIds)
                        ->exists();

                    if ($hasTransactions) {
                        return back()->with('error',
                            'Beberapa supplier tidak dapat dihapus karena memiliki transaksi terkait.'
                        );
                    }

                    $suppliers->delete();
                    $message = "{$count} supplier berhasil dihapus.";
                    break;
            }

            DB::commit();

            return back()->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk action: ' . $e->getMessage());

            return back()
                ->with('error', 'Terjadi kesalahan saat melakukan aksi bulk.');
        }
    }

    /**
     * Generate unique supplier code
     */
    private function generateSupplierCode(string $name): string
    {
        $prefix = 'SUP';
        $nameCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $date = now()->format('m');

        $lastSupplier = Supplier::where('code', 'like', $prefix . $nameCode . $date . '%')
                               ->latest('id')
                               ->first();

        if ($lastSupplier) {
            $lastNumber = intval(substr($lastSupplier->code, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $nameCode . $date . $newNumber;
    }
}
