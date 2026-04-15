<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique'   => 'Tên danh mục đã tồn tại.',
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $category->update($request->only('name', 'description'));

        return back()->with('success', 'Danh mục đã được cập nhật.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa.');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }
}
