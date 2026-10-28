<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = min($request->integer('per_page', 10), 50);

        $products = Product::query()
            ->with('category')
            ->where('is_available', true)
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->boolean('featured'), fn ($q) => $q->where('is_featured', true))
            ->when($request->boolean('discounted'), fn ($q) => $q->where('discount_percent', '>', 0))
            ->latest()
            ->paginate($perPage);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        abort_unless($product->is_available, 404);

        return new ProductResource($product->load('category'));
    }
}