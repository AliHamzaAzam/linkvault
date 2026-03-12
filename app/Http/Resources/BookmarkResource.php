<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
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
            'url' => $this->url,
            'title' => $this->title,
            'description' => $this->description,
            'favicon_url' => $this->favicon_url,
            'og_image_url' => $this->og_image_url,
            'site_name' => $this->site_name,
            'is_archived' => $this->is_archived,
            'meta_scraped_at' => $this->meta_scraped_at?->toIso8601String(),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'collections' => CollectionResource::collection($this->whenLoaded('collections')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
