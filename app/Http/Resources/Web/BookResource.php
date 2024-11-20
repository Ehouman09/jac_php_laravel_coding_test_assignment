<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'category' => new CategoryResource($this->whenLoaded('category')),
            'user' => new UserResource($this->whenLoaded('user')),
            'title' => $this->title,
            'name' => $this->name,
            'author' => $this->author,
            'description' => $this->description,
            'publication_year' => $this->publication_year,
            'cover_image' => $this->cover_image,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
        ];
    }
}
