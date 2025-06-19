<?php

namespace App\Services;

use App\Jobs\ProcessSubmission;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionService
{
    // Add your service methods here
    public function CheckUserSolvedProblem(User $user,Problem $problem): bool
    {
        return  $user->submissions()
            ->where('problem_id',$problem->id)
            ->where('status','accepted')->exists();

    }

    public function createProblemSubmission(Problem $problem,$data)
    {
        $submission = Submission::create([
            'problem_id' => $problem->id,
            'language' => $data['language'],
            'code' => $data['code'],
            'status' => 'pending',
            'user_id' => Auth::id()
        ]);
        ProcessSubmission::dispatch($submission);
        return $submission;

    }

    /// submit the contest solution and get the percentage from 100 for correct answers
    public function SubmitQuizContest($data,$contest): string
    {
        $questions = $contest->questions()->get();

        $correct = $this->getNumberOfCorrectAnswers($data['answers'], $questions, $contest);

        $this->AddUserToParticipants($contest,$correct);

        $questionsCount = $questions->count();

        return getPercentege($correct, $questionsCount);
    }


    protected function getNumberOfCorrectAnswers($answers, \Illuminate\Database\Eloquent\Collection $questions, Contest $contest): int
    {

        $correct = 0;
        foreach ($questions as $question) {
            $correctOption = $question->correctOption();
            if (isset($answers[$question->id]) && $correctOption && $answers[$question->id] == $correctOption->id) {
                $correct++;
            }
        }


        return $correct;
    }
    protected function AddUserToParticipants($contest,$correct): void
    {

        db::table('contest_user')->insert([
            'end_time' => now(), 'correct_answers' => $correct,
            'user_id' => Auth::user()->id,
            'contest_id' => $contest->id,
            'is_official' => $contest->status == 'active',
        ]);
    }
}
