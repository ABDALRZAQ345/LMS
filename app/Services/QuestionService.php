<?php

namespace App\Services;

class QuestionService
{
    // Add your service methods here
    public function addQuestionsToObject($object, $data): void
    {
        foreach ($data as $questionData) {
            $question = $object->questions()->create([
                'text' => $questionData['question'],
            ]);
            $correct = true;
            foreach ($questionData['options'] as $option) {
                $question->options()->create([
                    'answer' => $option,
                    'is_correct' => $correct,
                ]);
                $correct = false;
            }
        }
    }


}
