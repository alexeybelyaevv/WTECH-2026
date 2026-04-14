<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogProductIndexRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CatalogProductController extends Controller
{
    public function index(CatalogProductIndexRequest $request): AnonymousResourceCollection
    {
        $query = Product::query()
            ->active()
            ->with(['images', 'categories', 'platforms']);

        $ids = collect($request->validated('ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->values();

        if ($ids->isNotEmpty()) {
            $query->whereIn('id', $ids);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        $platformSlugs = $this->normalizeSlugs($request->input('platforms', []));
        if ($platformSlugs->isNotEmpty()) {
            $query->whereHas('platforms', function ($platformQuery) use ($platformSlugs): void {
                $platformQuery->whereIn('slug', $platformSlugs);
            });
        }

        $categorySlugs = $this->normalizeSlugs($request->input('categories', []));
        if ($categorySlugs->isNotEmpty()) {
            $query->whereHas('categories', function ($categoryQuery) use ($categorySlugs): void {
                $categoryQuery->whereIn('slug', $categorySlugs);
            });
        }

        if ($request->filled('q')) {
            $tokens = $this->tokenize((string) $request->string('q'));
            if ($tokens->isNotEmpty()) {
                $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

                $query->where(function ($searchQuery) use ($tokens, $operator): void {
                    foreach ($tokens as $token) {
                        $searchQuery->where(function ($tokenQuery) use ($token, $operator): void {
                            $like = '%'.$token.'%';

                            $tokenQuery
                                ->where('name', $operator, $like)
                                ->orWhere('description', $operator, $like);
                        });
                    }
                });
            }
        }

        match ($request->string('sort')->toString()) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate((int) $request->integer('per_page', 12))->withQueryString();

        return ProductListResource::collection($products);
    }

    public function show(string $slug): ProductDetailResource
    {
        $product = Product::query()
            ->active()
            ->with(['images', 'categories', 'platforms'])
            ->where('slug', $slug)
            ->firstOrFail();

        return new ProductDetailResource($product);
    }

    /**
     * @param  array<int, string>|string  $values
     * @return Collection<int, string>
     */
    private function normalizeSlugs(array|string $values): Collection
    {
        $raw = is_array($values) ? $values : explode(',', $values);

        return collect($raw)
            ->map(fn ($value) => Str::slug((string) $value))
            ->filter()
            ->values();
    }

    /**
     * @return Collection<int, string>
     */
    private function tokenize(string $query): Collection
    {
        return collect(preg_split('/\s+/u', trim($query)) ?: [])
            ->map(fn ($token) => trim((string) $token))
            ->filter(fn ($token) => $token !== '')
            ->values();
    }
}
