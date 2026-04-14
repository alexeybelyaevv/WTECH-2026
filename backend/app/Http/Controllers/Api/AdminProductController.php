<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStoreProductRequest;
use App\Http\Requests\AdminUpdateProductRequest;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductListResource;
use App\Models\Category;
use App\Models\Platform;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::query()
            ->with(['images', 'categories', 'platforms'])
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';
            $needle = '%'.trim((string) $request->input('q')).'%';

            $query->where(function ($productQuery) use ($needle, $operator): void {
                $productQuery
                    ->where('name', $operator, $needle)
                    ->orWhere('description', $operator, $needle)
                    ->orWhere('slug', $operator, $needle);
            });
        }

        $products = $query
            ->paginate((int) $request->integer('per_page', 20))
            ->withQueryString();

        return ProductListResource::collection($products);
    }

    public function show(Product $product): ProductDetailResource
    {
        return new ProductDetailResource($product->load(['images', 'categories', 'platforms']));
    }

    public function references(): JsonResponse
    {
        return response()->json([
            'categories' => Category::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'platforms' => Platform::query()->orderBy('name')->get(['id', 'name', 'slug']),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(AdminStoreProductRequest $request): ProductDetailResource
    {
        $product = DB::transaction(function () use ($request): Product {
            $data = $request->validated();

            $product = Product::query()->create([
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug($data['name']),
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'price' => $data['price'],
                'currency' => 'EUR',
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? true,
                'metadata' => $data['metadata'] ?? null,
            ]);

            $product->categories()->sync($data['category_ids']);
            $product->platforms()->sync($data['platform_ids']);

            $this->storeUploadedImages($product, $request->file('images', []));

            $this->assertMinimumImages($product);

            return $product->load(['images', 'categories', 'platforms']);
        });

        return new ProductDetailResource($product);
    }

    /**
     * @throws ValidationException
     */
    public function update(AdminUpdateProductRequest $request, Product $product): ProductDetailResource
    {
        $updatedProduct = DB::transaction(function () use ($request, $product): Product {
            $data = $request->validated();

            $product->update([
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug($data['name'], $product->id),
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'is_active' => $data['is_active'] ?? $product->is_active,
                'metadata' => $data['metadata'] ?? null,
            ]);

            $product->categories()->sync($data['category_ids']);
            $product->platforms()->sync($data['platform_ids']);

            if (! empty($data['remove_image_ids'])) {
                $imagesToDelete = $product->images()
                    ->whereIn('id', $data['remove_image_ids'])
                    ->get();

                foreach ($imagesToDelete as $image) {
                    $this->deleteImageFile($image->path);
                    $image->delete();
                }
            }

            if ($request->hasFile('images')) {
                $this->storeUploadedImages($product, $request->file('images', []));
            }

            $this->reindexImages($product);
            $this->assertMinimumImages($product);

            return $product->load(['images', 'categories', 'platforms']);
        });

        return new ProductDetailResource($updatedProduct);
    }

    /**
     * @throws ValidationException
     */
    public function destroyImage(Product $product, ProductImage $image): Response
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        if ($product->images()->count() <= 2) {
            throw ValidationException::withMessages([
                'image' => 'A product must contain at least 2 images.',
            ]);
        }

        $this->deleteImageFile($image->path);
        $image->delete();
        $this->reindexImages($product);

        return response()->noContent();
    }

    public function destroy(Product $product): Response
    {
        DB::transaction(function () use ($product): void {
            $images = $product->images()->get();
            foreach ($images as $image) {
                $this->deleteImageFile($image->path);
            }

            $product->delete();
        });

        return response()->noContent();
    }

    /**
     * @param  array<int, \Illuminate\Http\UploadedFile>  $images
     */
    private function storeUploadedImages(Product $product, array $images): void
    {
        $baseSortOrder = (int) $product->images()->max('sort_order');
        $nextSortOrder = $product->images()->exists() ? $baseSortOrder + 1 : 0;

        foreach ($images as $file) {
            $path = $file->store('product-images', 'public');

            ProductImage::query()->create([
                'product_id' => $product->id,
                'path' => $path,
                'alt' => $product->name,
                'sort_order' => $nextSortOrder,
            ]);

            $nextSortOrder++;
        }
    }

    private function deleteImageFile(string $path): void
    {
        Storage::disk('public')->delete($path);
    }

    /**
     * @throws ValidationException
     */
    private function assertMinimumImages(Product $product): void
    {
        if ($product->images()->count() < 2) {
            throw ValidationException::withMessages([
                'images' => 'A product must contain at least 2 images.',
            ]);
        }
    }

    private function reindexImages(Product $product): void
    {
        /** @var Collection<int, ProductImage> $images */
        $images = $product->images()->orderBy('sort_order')->orderBy('id')->get();
        foreach ($images as $index => $image) {
            if ((int) $image->sort_order !== $index) {
                $image->update(['sort_order' => $index]);
            }
        }
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'product';
        }

        $candidate = $base;
        $suffix = 1;

        while (Product::query()
            ->where('slug', $candidate)
            ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $suffix++;
            $candidate = $base.'-'.$suffix;
        }

        return $candidate;
    }
}
