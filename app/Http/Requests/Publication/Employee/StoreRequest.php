<?php

declare(strict_types=1);

namespace App\Http\Requests\Publication\Employee;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'publication_type_id' => ['required', 'integer', 'exists:publication_types,id'],
            'name_of_scientific_event' => ['required', 'string'],
            'author' => ['required', 'string'],
            'co_author' => ['nullable', 'string'],
            'publisher' => ['required', 'string'],
            'year' => ['required', 'integer'],
            'pages' => ['required', 'string'],
            'DOI_url' => ['nullable', 'string'],
            'bibliographic_description' => ['required', 'string'],
            'repository_url' => ['required', 'string'],
            'file' => ['nullable', File::types(['pdf','doc','docx'])]
        ];
    }
}
