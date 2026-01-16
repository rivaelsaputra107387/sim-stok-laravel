<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display stock report
     */
    public function index(Request $request)
    {
        // Validate filters
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'stock_status' => 'nullable|in:all,normal,low_stock,out_of_stock',
            'unit' => 'nullable|in:krg,dus'
        ]);

        // Get products with filters
        $query = Product::with(['category'])->where('is_active', true);

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($request->filled('stock_status') && $request->stock_status !== 'all') {
            switch ($request->stock_status) {
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

        $products = $query->orderBy('name')->get();

        // Calculate summary
        $summary = [
            'total_products' => $products->count(),
            'products_in_stock' => $products->where('current_stock', '>', 0)->count(),
            'low_stock_count' => $products->filter(fn($p) => $p->is_low_stock)->count(),
            'out_of_stock_count' => $products->filter(fn($p) => $p->is_out_of_stock)->count(),
            'total_krg_stock' => $products->where('unit', 'krg')->sum('current_stock'),
            'total_dus_stock' => $products->where('unit', 'dus')->sum('current_stock'),
        ];

        $data = [
            'products' => $products,
            'summary' => $summary,
            'filters' => $request->all(),
            'categories' => Category::where('is_active', true)->get(),
            'generated_at' => now()
        ];

        return view('admin.reports.index', $data);
    }

    public function stockPdf(Request $request)
    {
        // Validate filters
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'stock_status' => 'nullable|in:all,normal,low_stock,out_of_stock',
            'unit' => 'nullable|in:krg,dus'
        ]);

        // Get products with filters - same logic as index
        $query = Product::with(['category'])->where('is_active', true);

        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('unit')) {
            $query->where('unit', $request->unit);
        }

        if ($request->filled('stock_status') && $request->stock_status !== 'all') {
            switch ($request->stock_status) {
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

        $products = $query->orderBy('name')->get();

        // Calculate summary
        $summary = [
            'total_products' => $products->count(),
            'products_in_stock' => $products->where('current_stock', '>', 0)->count(),
            'low_stock_count' => $products->filter(fn($p) => $p->is_low_stock)->count(),
            'out_of_stock_count' => $products->filter(fn($p) => $p->is_out_of_stock)->count(),
            'total_krg_stock' => $products->where('unit', 'krg')->sum('current_stock'),
            'total_dus_stock' => $products->where('unit', 'dus')->sum('current_stock'),
        ];

        $data = [
            'products' => $products,
            'summary' => $summary,
            'filters' => $request->all(),
            'generated_at' => now()
        ];

        // Generate PDF dan stream (preview di browser)
        $pdf = FacadePdf::loadView('admin.reports.stock-pdf', $data)
            ->setPaper('a4', 'landscape') // Set paper size dan orientasi
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans'
            ]);

        // Stream PDF (preview di browser)
        return $pdf->stream('laporan-stok-' . now()->format('Y-m-d-His') . '.pdf');
    }
}
