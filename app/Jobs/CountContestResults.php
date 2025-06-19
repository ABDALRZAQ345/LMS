<?php

namespace App\Jobs;

use App\Models\Achievement;
use App\Models\Contest;
use App\Models\User;
use App\Services\AchievementsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CountContestResults implements ShouldQueue
{
    use Queueable;

    protected Contest $contest;
    protected AchievementsService $achievementsService;

    public function __construct($contest)
    {
        $this->contest = $contest;

    }

    /**
     * Execute the job.
     * @throws \Throwable
     */
    public function handle(): void
    {

        db::beginTransaction();
        try {
            // here we are getting students ordered by correct_answers then by end_time
            $students = $this->contest->students;
            $studentsCount = count($students);

            $StudentOrder = 1;
            foreach ($students as $student) {
                // get the points which that user obtain
                $gainedPoints = $this->getPointsForUser($StudentOrder, $studentsCount);
                // update his points and rank in contest
                $this->UpdateUserPointsAndRank($student, $StudentOrder, $gainedPoints);
                //move to the next user

                $this->handleAchievements($StudentOrder, $student, $studentsCount,$gainedPoints);
                $StudentOrder++;
            }


            Contest::where('id', $this->contest->id)->update(['status' => 'ended']);

            db::commit();
        } catch (\Exception $e) {
            db::rollBack();
        }

    }


    /**
     * if the user is from the first 50% he will get extra points equal to 100 minus his percentage
     * if he from the last 50% he will lose points equal to -percentage
     */
    public function getPointsForUser(int $UserOrder, int $studentsCount): int|float
    {
        $percentage = $UserOrder * 100 / $studentsCount;
        return $percentage <= 50 ? (101 - $percentage) : -1 * $percentage;

    }

    /**
     * @param mixed $student
     * @param int $i
     * @param float|int $gainedPoints
     * @return void
     */
    public function UpdateUserPointsAndRank(mixed $student, int $i, float|int $gainedPoints): void
    {
        db::table('contest_user')->where('contest_id', $this->contest->id)
            ->where('user_id', $student->id)
            ->update([
                'rank' => $i,
                'gained_points' => $gainedPoints,
            ]);
        $student = User::find($student->id);

        $student->increment('points', $gainedPoints);
        $student->update([
            'level' => $student->points <= 200 ? 'beginner' : ($student->points <= 500 ? 'intermediate' : ($student->points <= 900 ? 'advanced' : 'expert')),
            'points' => max($student->points,0),
        ]);
    }

    /**
     * @param int $StudentOrder
     * @param mixed $student
     * @param int $studentsCount
     * @return void
     */
    public function handleAchievements(int $StudentOrder, mixed $student, int $studentsCount,int $gainedPoints): void
    {
        $user = User::find($student->id);
        if ($StudentOrder <= 3) {
            $this->WonContest($user);
        }
        if ($studentsCount > 3 && $StudentOrder == $studentsCount) {
            $this->LoseContest($user);
        }
        if ($student->points <= 200 && $student->points + $gainedPoints > 200) {
            $this->achievementsService->ReachIntermediate($student);
        }
        if ($student->points == 0) {
            $this->achievementsService->FirstContest($student);
        }
    }
    public function WonContest(User $user): void
    {

        $achievement=Achievement::where('name','Winner Winner')->firstOrFail();
        $user->achievements()->syncWithoutDetaching($achievement->id);



    }
    public function LoseContest(User $user): void
    {

        $achievement=Achievement::where('name','Looser Looser')->firstOrFail();
        $user->achievements()->syncWithoutDetaching($achievement->id);



    }



}
