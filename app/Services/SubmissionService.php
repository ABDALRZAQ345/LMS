<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Jobs\ProcessSubmission;
use App\Models\Certificate;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\User;
use App\Repositories\Contest\ContestsRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;


class SubmissionService
{
    public  AchievementsService $achievementsService;

    public function __construct(AchievementsService  $achievementsService)
    {
        $this->achievementsService = $achievementsService;
    }
    // Add your service methods here
    public function CheckUserSolvedProblem(User $user, Problem $problem): bool
    {
        return $user->submissions()
            ->where('problem_id', $problem->id)
            ->where('status', 'accepted')->exists();

    }

    public function createProblemSubmission(Problem $problem, $data)
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
    public function SubmitQuizContest($data, $contest): string
    {
        $questions = $contest->questions()->get();

        $correct = $this->getNumberOfCorrectAnswers($data['answers'], $questions, $contest);

        $this->AddUserToParticipants($contest, $correct);

        $questionsCount = $questions->count();

        return getPercentage($correct, $questionsCount);
    }


    protected function getNumberOfCorrectAnswers($answers, \Illuminate\Database\Eloquent\Collection $questions): int
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

    protected function AddUserToParticipants($contest, $correct): void
    {

        db::table('contest_user')->insert([
            'end_time' => now(), 'correct_answers' => $correct,
            'user_id' => Auth::user()->id,
            'contest_id' => $contest->id,
            'is_official' => $contest->status == 'active',
        ]);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function submitTest($data, $test): string
    {
        $questions = $test->questions()->get();
        $test->loadCount('questions');

        $questionsCount = $test->questions_count;

        $this->HandleFinalTest($test);

        $correct = $this->getNumberOfCorrectAnswers($data['answers'], $questions);

        db::beginTransaction();
        try {
            $this->UpdateUserTestStatus($data['start_time'], $test, $correct);
            $percentage = getPercentage($correct, $questionsCount, true);
            $courseId=$test->course_id;
            $this->HandelCertificate($test, $percentage,$courseId);
            db::commit();
            return $percentage;
        } catch (Exception $exception) {
            db::rollBack();
            throw new ServerErrorException($exception->getMessage());
        }

    }

    protected function UpdateUserTestStatus($startTime, $test, $correct): void
    {
        db::table('test_user')->insert([
            'end_time' => now(),
            'correct_answers' => $correct,
            'user_id' => auth('api')->id(),
            'test_id' => $test->id,
            'start_time' => $startTime,
            'updated_at' => now(),
        ]);

    }

    protected function HandelCertificate($test, $percentage,$courseId): void
    {

        if ($test->is_final && $percentage >= 60) {
            Certificate::create([
                'course_id' => $test->course_id,
                'user_id' => auth('api')->id()
            ]);
            //todo need to be tested
            try{
                DB::table('course_user')->where('user_id', auth('api')->id())
                    ->where('course_id',$courseId)->update([
                        'status' => 'finished'
                    ]);
            }
            catch (Exception $exception){
            }
            $this->achievementsService->CompleteFirstCourse(auth('api')->user());
            if($percentage==100){
                $this->achievementsService->PerfectInFinalQuiz(auth('api')->user());
            }
        }

    }

    /**
     * @throws BadRequestException
     */
    protected function HandleFinalTest($test): void
    {
        if (!$test->is_final) return;


        if ( $test->getPercentageOfStudent(auth('api')->id()) >=60 ) {
            throw new BadRequestException('you cant retake final test when you pass it');
        }

        $pivot = $test->students()->where('user_id', auth('api')->id())
            ->orderByPivot('updated_at', 'desc')
            ->first()?->pivot;


        if ($pivot && $pivot->updated_at > (now()->subDay())->toDateTimeString() ) {
            throw new BadRequestException('you can`t take the final test more than once per day back again in ' .
                carbon::parse($pivot->updated_at)->addDay()->diffForHumans()
            );

        }

    }
}

