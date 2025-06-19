<?php

namespace App\Observers;

use App\Models\Contest;
use App\Models\Submission;

class SubmissionObserver
{
    /**
     * Handle the Submission "created" event.
     */
    public function created(Submission $submission): void
    {
        $contest=Contest::find($submission->problem->contest_id);
        $exists = \DB::table('contest_user')
            ->where('user_id', \Auth::id())
            ->where('contest_id', $contest->id)
            ->exists();

        if (!$exists) {
            \DB::table('contest_user')->insert([
                'user_id' => \Auth::id(),
                'contest_id' => $contest->id,
                'is_official' => $contest->status == 'active'
            ]);
        }

    }

    /**
     * Handle the Submission "updated" event.
     */
    public function updated(Submission $submission): void
    {
        //
    }

    /**
     * Handle the Submission "deleted" event.
     */
    public function deleted(Submission $submission): void
    {
        //
    }

    /**
     * Handle the Submission "restored" event.
     */
    public function restored(Submission $submission): void
    {
        //
    }

    /**
     * Handle the Submission "force deleted" event.
     */
    public function forceDeleted(Submission $submission): void
    {
        //
    }
}
