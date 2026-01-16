<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query pencarian tidak boleh kosong'
            ]);
        }

        $results = [];

        // Search Products
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->with('category')
            ->active()
            ->limit(5)
            ->get();

        foreach ($products as $product) {
            $results[] = [
                'type' => 'product',
                'title' => $product->name,
                'subtitle' => "Kode: {$product->code} | Kategori: {$product->category->name}",
                'stock' => $product->current_stock,
                'unit' => $product->unit_text,
                'url' => route('admin.products.show', $product->id),
                'icon' => 'fas fa-box'
            ];
        }

        // Search Categories
        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->active()
            ->limit(3)
            ->get();

        foreach ($categories as $category) {
            $results[] = [
                'type' => 'category',
                'title' => $category->name,
                'subtitle' => "Kode: {$category->code}",
                'url' => route('admin.categories.show', $category->id),
                'icon' => 'fas fa-tags'
            ];
        }

        // Search Suppliers
        $suppliers = Supplier::where('name', 'LIKE', "%{$query}%")
            ->orWhere('code', 'LIKE', "%{$query}%")
            ->active()
            ->limit(3)
            ->get();

        foreach ($suppliers as $supplier) {
            $results[] = [
                'type' => 'supplier',
                'title' => $supplier->name,
                'subtitle' => "Kode: {$supplier->code}",
                'url' => route('admin.suppliers.show', $supplier->id),
                'icon' => 'fas fa-truck'
            ];
        }

        return response()->json([
            'success' => true,
            'results' => $results,
            'total' => count($results)
        ]);
    }
}
