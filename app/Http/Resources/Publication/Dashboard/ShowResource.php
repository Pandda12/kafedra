<?php

declare(strict_types=1);

namespace App\Http\Resources\Publication\Dashboard;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $publicationType = $this->publicationType()->first();
        $file = $this->file()->first();
        $user = $this->createdBy()->first();

        return [
            'name' => $this->name,
            'author' => $this->author,
            'co_author' => $this->co_author ?? '-',
            'publication_type' => $publicationType ? $publicationType->name : null,
            'name_of_scientific_event' => $this->name_of_scientific_event,
            'publisher' => $this->publisher,
            'year' => $this->year,
            'pages' => $this->pages,
            'DOI_url' => $this->DOI_url,
            'bibliographic_description' => $this->bibliographic_description,
            'repository_url' => $this->repository_url,
            'file' => $file ? [
                'id' => $file->id,
                'name' => $file->name,
                'extension' => $file->extension,
                'size' => File::bytesToHuman( $file->size )
            ] : null,

            'created_by' => $user ? $user->first_name . ' ' . $user->second_name : null
        ];
    }
}
