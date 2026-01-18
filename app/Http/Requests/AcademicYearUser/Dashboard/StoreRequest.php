<?php

declare(strict_types=1);

namespace App\Http\Requests\AcademicYearUser\Dashboard;

use App\Enums\Roles;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'user' => ['required', 'integer', 'exists:users,id'],
            'role' => [
                'required',
                'string',
                Rule::in(
                    Roles::FULL_TIME_EMPLOYEE->value,
                    Roles::PART_TIME_EMPLOYEE->value
                )
            ]
        ];
    }
}
