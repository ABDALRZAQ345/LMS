<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChargeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'payment_method_id' => [
                'required',
                'string',
                Rule::in([
                    'pm_card_visa',
                    'pm_card_mastercard',
                    'pm_card_amex',
                ]),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],
        ];
    }

}
