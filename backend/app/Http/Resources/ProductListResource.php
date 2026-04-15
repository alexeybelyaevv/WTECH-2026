<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \App\Models\Product
 */
class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $previewPath = $this->images->first()?->path;

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
            'preview_image' => $previewPath,
            'preview_image_url' => $previewPath ? Storage::disk('public')->url($previewPath) : null,
            'categories' => $this->categories->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ])->values(),
            'platforms' => $this->platforms->map(fn ($platform) => [
                'id' => $platform->id,
                'name' => $platform->name,
                'slug' => $platform->slug,
            ])->values(),
        ];
    }
}
