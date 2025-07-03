<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Models\Course;
use App\Models\Test;
use App\Models\Video;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\DB;

class TestService
{
    protected QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function createTest(Course $course, $data): void
    {
        db::beginTransaction();
        try {
            $order = $course->content()->count() + 1;
            if ($data['is_final'] == 1 && $course->tests()->where('is_final', 1)->exists()) {
                throw new BadRequestException('there is already a final test');
            }


            $finalTest=Test::where('course_id',$course->id)->where('is_final',1)->first();

            if($finalTest){
                //todo do the same on adding video
                $order--;
                $finalTest->increment('order');
            }

            $test = Test::create([
                'title' => $data['title'],
                'order' => $order,
                'is_final' => $data['is_final'],
                'course_id' => $course->id
            ]);
            $this->questionService->addQuestionsToObject($test,$data['questions']);
            db::commit();
        } catch (Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function UpdateTest(Test $test, $data): void
    {
        db::beginTransaction();
        try {

            $test->update(['title' => $data['title']]);
            $test->questions()->delete();
            $this->questionService->addQuestionsToObject($test, $data['questions']);
            db::commit();
        } catch (Exception $e) {
            db::rollBack();
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function deleteTest(Test $test): void
    {
        // reorder tests and videos after deleted test
        Test::where('course_id',$test->course_id)->where('order','>',$test->order)->decrement('order');
        Video::where('course_id',$test->course_id)->where('order','>',$test->order)->decrement('order');
        $test->delete();
    }
}
