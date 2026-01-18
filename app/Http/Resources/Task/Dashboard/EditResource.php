<?php

declare(strict_types=1);

namespace App\Http\Resources\Task\Dashboard;

use App\Enums\UserSelectType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $assigned_at = $this->assigned_type === UserSelectType::PERSONAL->value
            ? $this->users()->pluck('user_id')->toArray()
            : [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'starts_on' => $this->starts_on->toDateString(),
            'ends_on' => $this->ends_on->toDateString(),
            'assigned_type' => $this->assigned_type,
            'assigned_at' => $assigned_at,
            'rating' => $this->rating,
        ];
    }
}
