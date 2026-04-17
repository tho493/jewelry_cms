<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $products = $this->productService->getAllForAdmin($search);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->store($request->validated());

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Sản phẩm đã được tạo thành công. Hãy thêm ảnh bên dưới.');
    }

    public function edit(Product $product)
    {
        $product->load('media', 'category');
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update($product, $request->validated());

        return back()->with('success', 'Sản phẩm đã được cập nhật.');
    }

    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa.');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }
}
