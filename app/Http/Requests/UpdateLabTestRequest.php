<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLabTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('updateResult', $this->route('labTest'));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'result' => ['nullable', 'string', 'required_if:status,completed'],
        ];
    }
}
