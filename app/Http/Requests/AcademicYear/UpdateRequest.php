<?php

declare(strict_types=1);

namespace App\Http\Requests\AcademicYear;

use App\Models\AcademicYear;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', AcademicYear::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:academic_years,id'],
            'name' => ['required', 'string'],
            'is_active' => ['required', 'boolean:strict'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'description' => ['nullable', 'string']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Необходимо указать название',
            'starts_on.required' => 'Необходимо указать дату начала',
            'ends_on.required' => 'Необходимо указать дату конца',
            'ends_on.after_or_equal' => 'Дата конца должна быть датой, следующей за началом или равной ему'
        ];
    }
}
