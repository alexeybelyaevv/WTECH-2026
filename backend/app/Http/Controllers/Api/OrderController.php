<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function options(): JsonResponse
    {
        $shippingPrices = Order::shippingPrices();

        return response()->json([
            'currency' => 'EUR',
            'payment_methods' => Order::paymentMethods(),
            'shipping_methods' => collect(Order::shippingMethods())->map(fn (string $method) => [
                'code' => $method,
                'price' => $shippingPrices[$method] ?? 0.0,
            ])->values(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(StoreOrderRequest $request): OrderResource
    {
        $result = DB::transaction(function () use ($request): Order {
            $normalizedItems = $this->normalizeItems($request->validated('items'));

            $products = Product::query()
                ->active()
                ->whereIn('id', $normalizedItems->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== $normalizedItems->count()) {
                throw ValidationException::withMessages([
                    'items' => 'One or more selected products are invalid or unavailable.',
                ]);
            }

            $orderRows = [];
            $subtotal = 0.0;
            $containsPhysical = false;

            foreach ($normalizedItems as $productId => $quantity) {
                /** @var Product $product */
                $product = $products->get($productId);

                if ($quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'items' => "Not enough stock for {$product->name}. Available: {$product->stock}.",
                    ]);
                }

                if ($product->type === Product::TYPE_PHYSICAL) {
                    $containsPhysical = true;
                }

                $unitPrice = (float) $product->price;
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;

                $orderRows[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }

            $shippingMethod = $request->input('shipping_method');
            $shippingTotal = 0.0;
            $shippingPrices = Order::shippingPrices();

            if ($containsPhysical) {
                if (! $shippingMethod) {
                    throw ValidationException::withMessages([
                        'shipping_method' => 'Shipping method is required for physical products.',
                    ]);
                }

                if ($shippingMethod === Order::SHIPPING_EMAIL) {
                    throw ValidationException::withMessages([
                        'shipping_method' => 'Email delivery is available only for digital products.',
                    ]);
                }

                $shippingTotal = (float) ($shippingPrices[$shippingMethod] ?? -1);
                if ($shippingTotal < 0) {
                    throw ValidationException::withMessages([
                        'shipping_method' => 'Selected shipping method is not supported.',
                    ]);
                }
            } else {
                $shippingMethod = Order::SHIPPING_EMAIL;
            }

            $promoCode = null;
            $discountTotal = 0.0;

            if ($request->filled('promo_code')) {
                $code = mb_strtoupper(trim((string) $request->input('promo_code')));

                $promoCode = PromoCode::query()
                    ->whereRaw('UPPER(code) = ?', [$code])
                    ->first();

                if (! $promoCode || ! $promoCode->isCurrentlyValid()) {
                    throw ValidationException::withMessages([
                        'promo_code' => 'Promo code is invalid or expired.',
                    ]);
                }

                if ($promoCode->type === PromoCode::TYPE_PERCENT) {
                    $discountTotal = round($subtotal * ((float) $promoCode->value / 100), 2);
                }

                if ($promoCode->type === PromoCode::TYPE_FIXED) {
                    $discountTotal = (float) $promoCode->value;
                }

                $discountTotal = min($subtotal, $discountTotal);
            }

            $grandTotal = round(max(0.0, $subtotal - $discountTotal + $shippingTotal), 2);

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => auth()->id(),
                'promo_code_id' => $promoCode?->id,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->string('payment_method')->toString(),
                'shipping_method' => $shippingMethod,
                'currency' => 'EUR',
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'shipping_total' => $shippingTotal,
                'grand_total' => $grandTotal,
                'customer_full_name' => $request->string('customer_full_name')->toString(),
                'customer_email' => $request->string('customer_email')->toString(),
                'customer_phone' => $request->string('customer_phone')->toString(),
                'country' => $request->string('country')->toString(),
                'city' => $request->string('city')->toString(),
                'address' => $request->string('address')->toString(),
                'zip_code' => $request->string('zip_code')->toString(),
                'notes' => $request->string('notes')->toString() ?: null,
                'placed_at' => now(),
            ]);

            foreach ($orderRows as $row) {
                /** @var Product $product */
                $product = $row['product'];
                $quantity = $row['quantity'];

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_type' => $product->type,
                    'unit_price' => $row['unit_price'],
                    'quantity' => $quantity,
                    'line_total' => $row['line_total'],
                    'metadata' => ['slug' => $product->slug],
                ]);

                $product->decrement('stock', $quantity);
            }

            if ($promoCode) {
                $promoCode->increment('used_count');
            }

            return $order->load(['items', 'promoCode']);
        });

        return new OrderResource($result);
    }

    public function show(string $orderNumber): OrderResource
    {
        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->with(['items', 'promoCode'])
            ->firstOrFail();

        return new OrderResource($order);
    }

    /**
     * @param  array<int, array{product_id:int|string, quantity:int|string}>  $items
     * @return Collection<int, int>
     */
    private function normalizeItems(array $items): Collection
    {
        return collect($items)
            ->map(fn (array $item) => [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
            ])
            ->groupBy('product_id')
            ->map(fn (Collection $rows) => $rows->sum('quantity'));
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}

