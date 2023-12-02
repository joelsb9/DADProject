<?php

namespace App\Http\Requests;

use App\Models\Vcard;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
        //$id = $this->route('transaction'); // Assumes your route parameter is named 'transaction'

        return [
            'vcard' => 'sometimes|exists:vcards,phone_number',
            'type' => 'sometimes|in:C,D',
            'value' => [
                'sometimes',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');
                    $vcard = $this->input('vcard');

                    if ($type === 'D') {
                        // For debit transactions, check if the value is less than or equal to the vCard balance
                        // You need to replace 'balance' with the actual column name storing the balance in your vCard model
                        $maxDebit = Vcard::where('phone_number', $vcard)->value('max_debit');

                        if ($value > $maxDebit) {
                            $fail('Debit value exceeds the maximum debit limit for the vCard.');
                        }
                    }
                },
            ],
            'payment_type' => 'sometimes|in:VCARD,MBWAY,PAYPAL,IBAN,MB,VISA',
            'payment_reference' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    $paymentType = $this->input('payment_type');

                    switch ($paymentType) {
                        case 'VCARD':
                            // Validate as a Portuguese phone number relative to an existing vCard
                            'regex:/^9\d{8}$/|exists:vcards,phone_number';
                            break;

                        case 'MBWAY':
                            // Validate as any Portuguese phone number
                            'regex:/^9\d{8}$/';
                            break;

                        case 'PAYPAL':
                            // Validate as a valid email
                            'email';
                            break;

                        case 'IBAN':
                            // Validate as 2 letters followed by 23 digits
                            'regex:/^[A-Z]{2}\d{23}$/';
                            break;

                        case 'MB':
                            // Validate as 5 digits, hyphen, and 9 digits
                            'regex:/^\d{5}-\d{9}$/';
                            break;

                        case 'VISA':
                            // Validate as 16 digits starting with the digit 4
                            'regex:/^4\d{15}$/';
                            break;

                        default:
                            $fail('Invalid payment type.');
                            break;
                    }
                },
            ],
            'pair_transaction' => [
                'sometimes',
                'required_if:payment_type,VCARD',
                'exists:transactions,id',
                Rule::notIn([$this->input('id')])
            ],
            'pair_vcard' => 'sometimes|required_if:payment_type,VCARD|exists:vcards,phone_number',

            'category_id' => [
                'sometimes',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    // Custom validation logic for 'category_id'
                    $type = $this->input('type');

                    if ($type) {
                        // Ensure that credit transactions only use credit categories
                        if ($type === 'C') {
                            $categoryType = Category::where('id', $value)->value('type');

                            if ($categoryType !== 'credit') {
                                $fail('Credit transactions must use credit categories.');
                            }
                        }

                        // Ensure that debit transactions only use debit categories
                        if ($type === 'D') {
                            $categoryType = Category::where('id', $value)->value('type');

                            if ($categoryType !== 'debit') {
                                $fail('Debit transactions must use debit categories.');
                            }
                        }
                    }
                },
            ],
            'description' => 'sometimes|string'
        ];
    }
}
