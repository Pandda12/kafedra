<?php

declare(strict_types=1);

namespace App\Http\Requests\Notification;

use App\Enums\TaskStatus;
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
        $user = $this->user();

        return [
            'task_id' => [
                Rule::requiredIf(!$user->isAdmin()),
                'integer',
            ],
            'status' => [
                'required',
                'string',
                Rule::in([
                    TaskStatus::ASSIGNED->value,
                    TaskStatus::ACCEPTED->value,
                    TaskStatus::REVISED->value,
                    TaskStatus::REVIEW->value,
                    TaskStatus::REJECTED->value,
                    TaskStatus::CLOSED->value
                ]),
            ],
            'type' => ['nullable', Rule::in(['update', 'read'])]
        ];
    }
}
