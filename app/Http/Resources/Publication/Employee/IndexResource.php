<?php

declare(strict_types=1);

namespace App\Http\Resources\Publication\Employee;

use App\Models\File;
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
        if ( $this->file_id ) {
            $file = File::find( $this->file_id );

            $fileData = $file ? [
                'name' => $file->name,
                'type' => $file->extension,
                'size' => File::bytesToHuman( $file->size ),
            ] : null;
        } else {
            $fileData = null;
        }

        return [
            'bibliographic_description' => $this->bibliographic_description,
            'repository_url' => $this->repository_url,
            'date_added' => $this->created_at->format('d.m.Y'),
            'file' => $fileData
        ];
    }
}
