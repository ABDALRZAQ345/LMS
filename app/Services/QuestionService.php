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

            foreach ($questionData['options'] as $option) {
                $question->options()->create([
                    'answer' => $option['option'],
                    'is_correct' => $option['is_true'],
                ]);

            }
        }
    }


}
