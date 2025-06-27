<?php

namespace App\Rules\Contest;

use App\Models\Contest;
use App\Models\Option;
use App\Models\Test;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SubmitQuizRule implements ValidationRule
{
    protected  $object;

    public function __construct(Contest|Test $object)
    {
        $this->object = $object;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(mixed $attribute, mixed $value, Closure $fail): void
    {

        $answers = $value;

        $contestQuestions = $this->object->questions()->get();

        foreach ($contestQuestions as $question) {
            if (! isset($answers[$question->id]) || ! Option::where('question_id', $question->id)->where('id', $answers[$question->id])->exists()) {
                $fail('answers array is invalid maybe the question id  is not for that quiz or option is not for that question ');
            }
        }
    }
}
