<?php

namespace App\Jobs;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class CountContestResults implements ShouldQueue
{
    use Queueable;

    protected Contest $contest;

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
            // here we are getting students ordered by correct_answers then by time
            $students = $this->contest->students;
            $studentsCount = count($students);

            $StudentOrder = 1;
            foreach ($students as $student) {
                // get the points which that user obtain
                $gainedPoints = $this->getPointsForUser($StudentOrder, $studentsCount);
                // update his points and rank in contest
                $this->UpdateUserPointsAndRank($student, $StudentOrder, $gainedPoints);
                //move to the next user
                $StudentOrder++;
            }

            Contest::where('id', $this->contest->id)->update(['status' => 'ended']);

            db::commit();
        } catch (\Exception $e) {
            db::rollBack();
        }

    }


    /**
        if the user is from the first 50% he will get extra points equal to 100 minus his percentage
        if he from the last 50% he will lose points equal to -percentage
      */
    public function getPointsForUser(int $UserOrder, int $studentsCount): int|float
    {
        $percentage = $UserOrder * 100 / $studentsCount;
        return $percentage <= 50 ? (100 - $percentage) : -1 * $percentage;

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
    }
}
