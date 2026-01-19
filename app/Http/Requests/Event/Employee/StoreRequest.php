<?php

declare(strict_types=1);

namespace App\Http\Requests\Event\Employee;

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
            'name' => ['required', 'string'],
            'place' => ['required', 'string'],
            'date' => ['required', 'date'],
            'participation_form_id' => ['required', 'exists:participation_forms,id'],
            'title_of_the_report' => ['nullable', 'string']
        ];
    }
}
