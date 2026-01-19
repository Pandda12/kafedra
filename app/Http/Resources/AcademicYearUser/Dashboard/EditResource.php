<?php

declare(strict_types=1);

namespace App\Http\Resources\AcademicYearUser\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditResource extends JsonResource
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
            'user' => $this->user_id,
            'role' => $this->role
        ];
    }
}
