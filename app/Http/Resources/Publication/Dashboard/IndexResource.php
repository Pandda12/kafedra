<?php

declare(strict_types=1);

namespace App\Http\Resources\Publication\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
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
            'bibliographic_description' => $this->bibliographic_description,
            'repository_url' => $this->repository_url,
            'date_added' => $this->created_at->format('d.m.Y'),
            'file' => !empty( $this->file()->get()->toArray() )
        ];
    }
}
