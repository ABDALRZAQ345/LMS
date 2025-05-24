<?php

namespace App\Http\Requests;

use App\Rules\SubmitContestQuizRule;
use Illuminate\Foundation\Http\FormRequest;

class SubmitContestRequest extends FormRequest
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
        $contest = $this->route('contest');

        return [
            'answers' => ['required', 'array', new SubmitContestQuizRule($contest)],
            'answers.*' => ['required', 'integer', 'exists:options,id'],

        ];
    }
}
