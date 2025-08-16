<?php

namespace App\Http\Requests;

use App\Rules\Contest\SubmitQuizRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitTestRequest extends FormRequest
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
    public function rules(): array
    {
        $test = $this->route('test');

        return [
            'answers' => ['required', 'array', new SubmitQuizRule($test)],
            'answers.*' => ['required', 'integer', 'exists:options,id'],
            'start_time' => ['required', 'date','before_or_equal:now'],
        ];
    }
}
