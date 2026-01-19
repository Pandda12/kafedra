<?php

declare(strict_types=1);

namespace App\Http\Requests\Option;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'task_date_color' => ['required', 'array'],
            'task_date_color.red' => ['required', 'integer', 'min:1'],
            'task_date_color.yellow' => ['required', 'integer', 'min:1', 'gt:task_date_color.red'],
            'task_date_color.green' => ['required', 'integer', 'min:1', 'gt:task_date_color.yellow'],
            'publication_rating' => ['required', 'integer', 'min:1'],
            'event_rating' => ['required', 'integer', 'min:1']
        ];
    }
}
