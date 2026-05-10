<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'items' => $this->cartFor($request)->items,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $items = $this->validatedItems($request);
        $cart = $this->cartFor($request);
        $cart->update(['items' => $items]);

        return response()->json([
            'items' => $cart->fresh()->items,
        ]);
    }

    public function merge(Request $request): JsonResponse
    {
        $incomingItems = $this->validatedItems($request);
        $cart = $this->cartFor($request);
        $items = $cart->items ?? [];

        foreach ($incomingItems as $productId => $quantity) {
            $items[$productId] = min(100, ((int) ($items[$productId] ?? 0)) + $quantity);
        }

        $cart->update(['items' => $this->filterExistingProducts($items)]);

        return response()->json([
            'items' => $cart->fresh()->items,
        ]);
    }

    private function cartFor(Request $request): UserCart
    {
        return UserCart::query()->firstOrCreate(
            ['user_id' => $request->user()->id],
            ['items' => []],
        );
    }

    /**
     * @return array<string, int>
     */
    private function validatedItems(Request $request): array
    {
        $validated = $request->validate([
            'items' => ['nullable', 'array'],
            'items.*' => ['integer', 'min:1', 'max:100'],
        ]);

        return $this->filterExistingProducts((array) ($validated['items'] ?? []));
    }

    /**
     * @param  array<string|int, mixed>  $items
     * @return array<string, int>
     */
    private function filterExistingProducts(array $items): array
    {
        $normalized = [];

        foreach ($items as $productId => $quantity) {
            $productId = (int) $productId;
            $quantity = (int) $quantity;

            if ($productId > 0 && $quantity > 0) {
                $normalized[(string) $productId] = min(100, $quantity);
            }
        }

        if ($normalized === []) {
            return [];
        }

        $existingIds = Product::query()
            ->active()
            ->whereIn('id', array_keys($normalized))
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        return array_intersect_key($normalized, array_flip($existingIds));
    }
}
