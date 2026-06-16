<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth is handled by route middleware
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status'      => ['required', 'in:pending,in_progress,completed'],
            'priority'    => $user->isPro()
                                ? ['required', 'in:low,medium,high']
                                : ['nullable'],
            'category'    => $user->isPro()
                                ? ['nullable', 'string', 'max:100']
                                : ['nullable'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Task title is required.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
        ];
    }
}