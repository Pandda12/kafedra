<?php

declare(strict_types=1);

namespace App\Http\Requests\Task\Employee;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

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
        $status = $this->input('status');

        $requiresReport = $status === TaskStatus::REVIEW->value;

        return [
            'id' => ['required', 'integer'],
            'status' => [
                'required','string',
                Rule::in([
                    TaskStatus::ACCEPTED->value,
                    TaskStatus::REVISED->value,
                    TaskStatus::REVIEW->value,
                ]),
            ],
            'description' => [
                Rule::requiredIf($requiresReport),
                'nullable','string'
            ],
            'file' => [
                'nullable',
                File::types(['pdf','doc','docx'])->max(10 * 1024)
            ],
        ];
    }
}
