<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'   => Product::count(),
            'published'        => Product::published()->count(),
            'draft'            => Product::draft()->count(),
            'total_categories' => Category::count(),
            'total_media'      => Media::count(),
        ];

        $latestProducts = Product::with('category')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestProducts'));
    }
}
