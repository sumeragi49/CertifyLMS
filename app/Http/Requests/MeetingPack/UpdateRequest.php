<?php

namespace App\Http\Requests\MeetingPack;

use App\Models\MeetingPack;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('plan')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'meeting_count' => ['required', 'integer', 'between:1,100'],
            'price' => ['required', 'integer', 'between:0,1000000'],
            'stripe_price_id' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required','integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'SKU名',
            'description' => '説明',
            'meeting_count' => '面談回数',
            'price' => '価格',
            'stripe_price_id' => 'Stripe Price ID',
            'sort_order' => '数値',
        ];
    }
}
