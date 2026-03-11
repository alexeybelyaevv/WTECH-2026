<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStorePromoCodeRequest;
use App\Http\Requests\AdminUpdatePromoCodeRequest;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminPromoCodeController extends Controller
{
    public function index(): JsonResponse
    {
        $items = PromoCode::query()
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($items);
    }

    /**
     * @throws ValidationException
     */
    public function store(AdminStorePromoCodeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->validatePromoValue($data['type'], (float) $data['value']);

        $promo = PromoCode::query()->create([
            'code' => Str::upper(trim($data['code'])),
            'type' => $data['type'],
            'value' => $data['value'],
            'currency' => Str::upper($data['currency'] ?? 'EUR'),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json($promo, 201);
    }

    /**
     * @throws ValidationException
     */
    public function update(AdminUpdatePromoCodeRequest $request, PromoCode $promoCode): JsonResponse
    {
        $data = $request->validated();
        $this->validatePromoValue($data['type'], (float) $data['value']);

        $promoCode->update([
            'code' => Str::upper(trim($data['code'])),
            'type' => $data['type'],
            'value' => $data['value'],
            'currency' => Str::upper($data['currency'] ?? $promoCode->currency),
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'is_active' => $data['is_active'] ?? $promoCode->is_active,
        ]);

        return response()->json($promoCode->refresh());
    }

    public function destroy(PromoCode $promoCode): Response
    {
        $promoCode->delete();

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    private function validatePromoValue(string $type, float $value): void
    {
        if ($type === PromoCode::TYPE_PERCENT && $value > 100) {
            throw ValidationException::withMessages([
                'value' => 'Percent promo code must be less than or equal to 100.',
            ]);
        }
    }
}

