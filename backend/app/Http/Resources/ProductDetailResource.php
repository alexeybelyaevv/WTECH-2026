<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Product
 */
class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'price' => (float) $this->price,
            'currency' => $this->currency,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata,
            'categories' => $this->categories->map(fn ($category) => [
                'name' => $category->name,
                'slug' => $category->slug,
            ])->values(),
            'platforms' => $this->platforms->map(fn ($platform) => [
                'name' => $platform->name,
                'slug' => $platform->slug,
            ])->values(),
            'images' => $this->images->map(fn ($image) => [
                'id' => $image->id,
                'path' => $image->path,
                'alt' => $image->alt,
                'sort_order' => $image->sort_order,
            ])->values(),
        ];
    }
}

