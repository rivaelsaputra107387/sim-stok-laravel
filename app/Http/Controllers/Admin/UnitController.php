<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UnitController extends Controller
{
    /**
     * Display a listing of units
     */
    public function index(Request $request): View
    {
        $query = Unit::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $validSortColumns = ['name', 'symbol', 'is_active', 'created_at'];
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $units = $query->withCount('products')->paginate(10);

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit
     */
    public function create(): View
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created unit in storage
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:units,name',
            'symbol' => 'nullable|string|max:10|unique:units,symbol',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama satuan wajib diisi.',
            'name.unique' => 'Nama satuan sudah ada.',
            'symbol.unique' => 'Simbol satuan sudah ada.'
        ]);

        // Set default value for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? true : false;

        try {
            Unit::create($validated);

            return redirect()
                ->route('admin.units.index')
                ->with('success', 'Satuan berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan satuan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified unit
     */
    public function show(Unit $unit): View
    {
        $unit->load(['products' => function ($query) {
            $query->select('id', 'name', 'code', 'unit_id', 'current_stock', 'is_active')
                  ->with('category:id,name')
                  ->latest();
        }]);

        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit
     */
    public function edit(Unit $unit): View
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage
     */
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'name')->ignore($unit->id)
            ],
            'symbol' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('units', 'symbol')->ignore($unit->id)
            ],
            'is_active' => 'boolean'
        ], [
            'name.required' => 'Nama satuan wajib diisi.',
            'name.unique' => 'Nama satuan sudah ada.',
            'symbol.unique' => 'Simbol satuan sudah ada.'
        ]);

        // Set default value for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? true : false;

        try {
            $unit->update($validated);

            return redirect()
                ->route('admin.units.index')
                ->with('success', 'Satuan berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui satuan. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified unit from storage
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        try {
            // Check if unit is being used by any products
            $productsCount = $unit->products()->count();

            if ($productsCount > 0) {
                return redirect()
                    ->back()
                    ->with('error', "Tidak dapat menghapus satuan karena masih digunakan oleh {$productsCount} produk.");
            }

            $unit->delete();

            return redirect()
                ->route('admin.units.index')
                ->with('success', 'Satuan berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus satuan. Silakan coba lagi.');
        }
    }

    /**
     * Toggle unit active status
     */
    public function toggle(Unit $unit): JsonResponse
    {
        try {
            // If trying to deactivate, check if unit is being used by active products
            if ($unit->is_active) {
                $activeProductsCount = $unit->products()->where('is_active', true)->count();

                if ($activeProductsCount > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat menonaktifkan satuan karena masih digunakan oleh {$activeProductsCount} produk aktif."
                    ], 400);
                }
            }

            $unit->update(['is_active' => !$unit->is_active]);

            $status = $unit->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => "Satuan berhasil {$status}.",
                'is_active' => $unit->is_active
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status satuan.'
            ], 500);
        }
    }

    /**
     * Get units for API/AJAX calls
     */
    public function getUnits(Request $request): JsonResponse
    {
        $query = Unit::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%");
            });
        }

        $units = $query->select('id', 'name', 'symbol')
                      ->orderBy('name')
                      ->get();

        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Bulk delete units
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:units,id'
        ]);

        try {
            $units = Unit::whereIn('id', $request->ids)->get();
            $cannotDelete = [];
            $deleted = 0;

            foreach ($units as $unit) {
                $productsCount = $unit->products()->count();

                if ($productsCount > 0) {
                    $cannotDelete[] = "{$unit->name} (digunakan oleh {$productsCount} produk)";
                } else {
                    $unit->delete();
                    $deleted++;
                }
            }

            $message = "{$deleted} satuan berhasil dihapus.";

            if (!empty($cannotDelete)) {
                $message .= " Tidak dapat menghapus: " . implode(', ', $cannotDelete);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus satuan yang dipilih.'
            ], 500);
        }
    }

    /**
     * Bulk toggle status
     */
    public function bulkToggle(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:units,id',
            'status' => 'required|boolean'
        ]);

        try {
            $units = Unit::whereIn('id', $request->ids)->get();
            $cannotToggle = [];
            $updated = 0;

            foreach ($units as $unit) {
                // If trying to deactivate, check if unit is being used by active products
                if (!$request->status && $unit->is_active) {
                    $activeProductsCount = $unit->products()->where('is_active', true)->count();

                    if ($activeProductsCount > 0) {
                        $cannotToggle[] = "{$unit->name} (digunakan oleh {$activeProductsCount} produk aktif)";
                        continue;
                    }
                }

                $unit->update(['is_active' => $request->status]);
                $updated++;
            }

            $status = $request->status ? 'diaktifkan' : 'dinonaktifkan';
            $message = "{$updated} satuan berhasil {$status}.";

            if (!empty($cannotToggle)) {
                $message .= " Tidak dapat diubah: " . implode(', ', $cannotToggle);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status satuan yang dipilih.'
            ], 500);
        }
    }
}
