<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Dashboard;

use App\Enums\Roles;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'second_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', Rule::in([
                Roles::ADMIN->value,
                Roles::EMPLOYEE->value
            ])],
            'is_active' => ['required', 'boolean']
        ];

        if ( $this->input('auth_step') === 2) {
            $rules['email'] = [
                Rule::requiredIf(fn () => $this->input('auth_step') === 2),
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->route('user'))
            ];

        }

        return $rules;
    }
}
