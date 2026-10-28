<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items' => CartItemResource::collection($this->items),
            'items_count' => (int) $this->items->sum('quantity'),
            'subtotal' => round(
                $this->items->sum(fn ($item) => $item->product->final_price * $item->quantity),
                2
            ),
        ];
    }
}