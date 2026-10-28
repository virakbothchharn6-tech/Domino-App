<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function currentCart(Request $request): Cart
    {
        $cart = $request->user()->cart()->firstOrCreate();

        return $cart->load('items.product.category');
    }

    public function show(Request $request)
    {
        return new CartResource($this->currentCart($request));
    }

    public function addItem(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        abort_unless($product->is_available, 422, 'This product is not available.');

        $cart = $request->user()->cart()->firstOrCreate();

        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->quantity = min(($item->exists ? $item->quantity : 0) + ($data['quantity'] ?? 1), 99);
        $item->save();

        return (new CartResource($this->currentCart($request)))
            ->response()
            ->setStatusCode(201);
    }

    public function updateItem(Request $request, CartItem $item)
    {
        abort_unless($item->cart->user_id === $request->user()->id, 404);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $item->update(['quantity' => $data['quantity']]);

        return new CartResource($this->currentCart($request));
    }

    public function removeItem(Request $request, CartItem $item)
    {
        abort_unless($item->cart->user_id === $request->user()->id, 404);

        $item->delete();

        return new CartResource($this->currentCart($request));
    }

    public function clear(Request $request)
    {
        $request->user()->cart()->firstOrCreate()->items()->delete();

        return new CartResource($this->currentCart($request));
    }
}