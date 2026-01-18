<?php

declare(strict_types=1);

namespace App\Http\Resources\Event\Employee;

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
            'name' => $this->name,
            'place' => $this->place,
            'date' => date('d.m.Y',strtotime($this->date)),
            'participation_form' => $this->participationForm->name,
            'title_of_the_report' => $this->title_of_the_report ?? '-',
            'date_added' => $this->created_at->format('d.m.Y')
        ];
    }
}
