<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Enums\Roles;
use App\Models\Task;
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
        $user = $this->user();

        $task = $this->route('task');

        if ( !$user || !$task ) {
            return false;
        }

        return $user->can('update', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:tasks,id'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'assigned_type' => [
                'required',
                'string',
                Rule::in([
                    'all',
                    'personal',
                    Roles::FULL_TIME_EMPLOYEE->value,
                    Roles::PART_TIME_EMPLOYEE->value,
                ]),
            ],
            'assigned_at' => [
                Rule::requiredIf(fn () => $this->input('assigned_type') === 'personal'),
                'array',
            ],
            'assigned_at.*' => ['integer', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:0']
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
            'ends_on.after_or_equal' => 'Дата конца должна быть датой, следующей за началом или равной ему',
            'assigned_type' => 'Необходимо указать для кого назначается задача',
            'assigned_at' => 'Необходимо указать сотрудников',
        ];
    }
}
