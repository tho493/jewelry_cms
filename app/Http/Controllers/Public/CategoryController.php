<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $products = $category->products()
            ->published()
            ->with('media')
            ->latest()
            ->paginate(12);

        return view('public.categories.show', compact('category', 'products'));
    }
}
