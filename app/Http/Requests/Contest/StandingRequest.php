<?php

namespace App\Http\Requests\Contest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StandingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Gate::allows('seeStanding', $this->route('contest'));;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'justFriends' => ['required','in:0,1,true,false'],
            'items' => ['nullable', 'integer', 'min:10', 'max:30'],
        ];
    }
    public function prepareForValidation(): void
    {
        $this->merge([
            'items' => $this->filled('items') ? $this->input('items') : 30,
        ]);
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
