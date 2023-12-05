<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVcardRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'photo_url' => 'sometimes|url',
            'password' => 'sometimes|string|min:3',
            'confirmation_code' => 'sometimes|string|min:3',
            'blocked' => 'sometimes|boolean',
            'balance' => 'sometimes|numeric|min:0',
            'max_debit' => 'sometimes|numeric|min:0',
            'custom_options' => 'nullable|json',
            'custom_data' => 'nullable|json'
        ];
    }
}
