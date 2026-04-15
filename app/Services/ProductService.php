<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProductService
{
    public function store(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        // Media files are deleted via Media model / MediaService
        $product->delete();
    }

    public function getPublished(int $perPage = 12): LengthAwarePaginator
    {
        return Product::published()
            ->with(['category', 'media' => fn ($q) => $q->where('is_cover', true)->orWhere('sort_order', 0)])
            ->latest()
            ->paginate($perPage);
    }

    public function getByCategory(string $categorySlug, int $perPage = 12): LengthAwarePaginator
    {
        return Product::published()
            ->whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
            ->with(['category'])
            ->latest()
            ->paginate($perPage);
    }

    public function getFeatured(int $limit = 8): \Illuminate\Database\Eloquent\Collection
    {
        return Product::published()
            ->with(['media' => fn ($q) => $q->where('is_cover', true)])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getAllForAdmin(int $perPage = 20): LengthAwarePaginator
    {
        return Product::with('category')
            ->latest()
            ->paginate($perPage);
    }
}
