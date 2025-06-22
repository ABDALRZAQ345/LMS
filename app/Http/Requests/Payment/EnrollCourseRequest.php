<?php

namespace App\Http\Requests\Payment;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCourseAmount;
use Illuminate\Validation\Rule;

class EnrollCourseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $course = $this->route('course');

        if ($course->price == 0) {
            return [];
        }

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
                function ($attribute, $value, $fail) use ($course) {
                    $expected = $course->price ;
                    if ($value != $expected) {
                        $fail("The amount provided ($value) does not match the course price ($expected).");
                    }
                },
            ],
        ];
    }

}
