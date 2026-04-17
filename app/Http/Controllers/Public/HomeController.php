<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ProductService;

class HomeController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index()
    {
        $featuredProducts = $this->productService->getFeatured(8);
        $categories       = Category::withCount('products')->orderBy('name')->get();
        $setting          = \App\Models\HomeSetting::instance();
        $slides           = \App\Models\HomeSlide::where('is_active', true)->orderBy('sort_order')->get();

        return view('public.home', compact('featuredProducts', 'categories', 'setting', 'slides'));
    }
}
