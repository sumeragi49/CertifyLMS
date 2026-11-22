<?php

namespace App\Http\Requests\Plan;

use App\Enums\PlanStatus;
use App\Models\Plan;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('viewAny', Plan::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(PlanStatus::class)],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
