<?php

namespace App\Http\Requests\Quiz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Gate::allows('editCourse',$this->route('course'));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
                 'title' => ['required', 'string', 'max:255'],
                 'questions' => ['required', 'array', 'min:1'],
                 'questions.*.question' => ['required', 'string'],
                 'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*' => ['required', 'array'],
            'questions.*.options.*.is_true' => ['required', 'in:0,1'],
            'questions.*.options.*.option' => ['required', 'string'],
             ];

    }
}
