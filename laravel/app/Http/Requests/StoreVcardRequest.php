<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVcardRequest extends FormRequest
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
            'phone_number' => 'required|regex:/^9\d{8}$/|unique:vcards,phone_number',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'photo_url' => 'sometimes',
            'password' => 'required|string|min:3',
            'confirmation_code' => 'required|string|min:3',
            'blocked' => 'sometimes|in:0,1',
            'balance' => 'sometimes|numeric|min:0',
            'max_debit' => 'sometimes|numeric|min:0',
            'custom_options' => 'nullable|json',
            'custom_data' => 'nullable|json',
            'base64ImagePhoto' => 'sometimes|string',
        ];
    }
}
