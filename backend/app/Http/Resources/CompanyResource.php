<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'industry' => $this->industry,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'files' => FileResource::collection($this->whenLoaded('files')),
            'notes' => NoteResource::collection($this->whenLoaded('notes'))
        ];
    }
}
