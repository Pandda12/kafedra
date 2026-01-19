<?php

declare(strict_types=1);

namespace App\Http\Resources\Task\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( Request $request ): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'starts_on' => $this->starts_on->format('d.m.Y'),
            'ends_on' => $this->ends_on->format('d.m.Y'),
            'assigned_count' => $this->assignees->count()
        ];
    }
}
