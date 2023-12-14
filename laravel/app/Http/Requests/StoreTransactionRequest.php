<?php

namespace App\Http\Requests;

use App\Models\Vcard;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'vcard' => 'required|exists:vcards,phone_number',
            'type' => 'required|in:C,D',
            'value' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');
                    $vcardPhoneNumber = $this->input('vcard');

                    // Retrieve the vCard instance
                    $vcard = Vcard::where('phone_number', $vcardPhoneNumber)->first();

                    if (!$vcard) {
                        $fail('Invalid vCard.');
                        return;
                    }

                    // For debit transactions, check if the value is less than or equal to the vCard balance
                    //$user = $this->user();
                    //TODO:
                    if ($type === 'D' /*&& $vcard->phone_number === $user->id*/) {
                        $vcardBalance = $vcard->balance;
                        $maxDebit = $vcard->max_debit;

                        // Ensure the value is equal or less than the vCard balance
                        if ($value > $vcardBalance) {
                            $fail('Debit value exceeds the vCard balance.');
                        }

                        // Ensure the value is equal or less than the maximum debit limit for the vCard
                        if ($value > $maxDebit) {
                            $fail('Debit value exceeds the maximum debit limit for the vCard.');
                        }
                    }
                },
            ],
            'payment_type' => 'required|in:VCARD,MBWAY,PAYPAL,IBAN,MB,VISA',
            'payment_reference' => [
                'required',
                function ($attribute, $value, $fail) {
                    $paymentType = $this->input('payment_type');
                    $vcardPhoneNumber = $this->input('vcard');

                    switch ($paymentType) {
                        case 'VCARD':
                            // Validate as a Portuguese phone number relative to an existing vCard
                            if (!(preg_match('/^9\d{8}$/', $value) && Vcard::where('phone_number', $value)->exists() && $value !== $vcardPhoneNumber)) {
                                $fail('Invalid payment reference for VCARD.');
                            }
                            break;

                        case 'MBWAY':
                            // Validate as any Portuguese phone number
                            if (!preg_match('/^9\d{8}$/', $value)) {
                                $fail('Invalid payment reference for MBWAY.');
                            }
                            break;

                        case 'PAYPAL':
                            // Validate as a valid email
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $fail('Invalid payment reference for PAYPAL.');
                            }
                            break;

                        case 'IBAN':
                            // Validate as 2 letters followed by 23 digits
                            if (!preg_match('/^[A-Z]{2}\d{23}$/', $value)) {
                                $fail('Invalid payment reference for IBAN.');
                            }
                            break;

                        case 'MB':
                            // Validate as 5 digits, hyphen, and 9 digits
                            if (!preg_match('/^\d{5}-\d{9}$/', $value)) {
                                $fail('Invalid payment reference for MB.');
                            }
                            break;

                        case 'VISA':
                            // Validate as 16 digits starting with the digit 4
                            if (!preg_match('/^4\d{15}$/', $value)) {
                                $fail('Invalid payment reference for VISA.');
                            }
                            break;

                        default:
                            $fail('Invalid payment type.');
                    }
                },
            ],

            //'pair_vcard' => 'nullable|exists:vcards,phone_number',

            'category_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    // Custom validation logic for 'category_id'
                    $type = $this->input('type');

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
                },
            ],
            'description' => 'nullable|string',
            'custom_options' => 'nullable|json',
            'custom_data' => 'nullable|json'
        ];
    }
}
