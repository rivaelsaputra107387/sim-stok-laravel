<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request): View
    {
        $query = Category::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");

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

        // Sort by
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowedSorts = ['name', 'code', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $categories = $query->withCount('products')->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:categories,code',

            'is_active' => 'boolean'
        ]);

        // Auto generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateCategoryCode($validated['name']);
        }

        // Ensure code is unique
        $validated['code'] = $this->ensureUniqueCode($validated['code']);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
 * Display the specified category
 */
public function show(Category $category): View
{
    $category->load(['products' => function ($query) {
        $query->with(['stockTransactions'])
              ->orderBy('name');
    }]);

    // Get products count by status
    $productsStats = [
        'total' => $category->products->count(),
        'active' => $category->products->where('is_active', true)->count(),
        'low_stock' => $category->products->filter(function ($product) {
            return $product->current_stock <= $product->minimum_stock && $product->current_stock > 0;
        })->count(),
        'out_of_stock' => $category->products->where('current_stock', 0)->count(),
        'expired' => $category->products->filter(function ($product) {
            return $product->expired_date && $product->expired_date->isPast();
        })->count(),
        'near_expiry' => $category->products->filter(function ($product) {
            return $product->expired_date && $product->expired_date->diffInDays(now()) <= 7 && $product->expired_date->isFuture();
        })->count()
    ];

    return view('admin.categories.show', compact('category', 'productsStats'));
}

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:categories,code,' . $category->id,
            'is_active' => 'boolean'
        ]);

        // Auto generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = $this->generateCategoryCode($validated['name']);
        }

        // Ensure code is unique (excluding current category)
        if ($validated['code'] !== $category->code) {
            $validated['code'] = $this->ensureUniqueCode($validated['code'], $category->id);
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk terkait!');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Toggle category status (active/inactive)
     */
    public function toggle(Category $category): RedirectResponse
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->back()
            ->with('success', "Kategori berhasil {$status}!");
    }

    /**
     * Generate category code from name
     */
    private function generateCategoryCode(string $name): string
    {
        // Convert to uppercase and replace spaces with underscores
        $code = strtoupper(Str::slug($name, '_'));

        // Limit to 10 characters
        return substr($code, 0, 10);
    }

    /**
     * Ensure the code is unique
     */
    private function ensureUniqueCode(string $code, ?int $excludeId = null): string
    {
        $originalCode = $code;
        $counter = 1;

        while (true) {
            $query = Category::where('code', $code);

            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            $code = $originalCode . '_' . $counter;
            $counter++;
        }

        return $code;
    }
}
