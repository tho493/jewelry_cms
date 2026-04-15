<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $query = Product::published()->with(['category', 'media']);

        // Filter by category
        if ($request->filled('danh_muc')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->danh_muc));
        }

        // Search
        if ($request->filled('tim_kiem')) {
            $search = $request->tim_kiem;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Sort
        match ($request->sap_xep) {
            'gia_tang'   => $query->orderBy('price', 'asc'),
            'gia_giam'   => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('public.products.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::published()
            ->with(['category', 'media'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::published()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('media')
            ->limit(4)
            ->get();

        return view('public.products.show', compact('product', 'related'));
    }
}
