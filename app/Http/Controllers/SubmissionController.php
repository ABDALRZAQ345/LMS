<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitContestRequest;
use App\Jobs\ProcessSubmission;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SubmissionController extends Controller
{

    public function submitProblem(Contest $contest,Problem $problem,Request $request)
    {
        $request->validate([
            'language' => 'required|in:cpp,python',
            'code' => 'required|string'
        ]);
        $problem=$contest->problems()->find($problem->id);
        $submission = Submission::create([
            'problem_id' => $problem->id,
            'language' => $request->language,
            'code' => $request->code,
            'status' => 'pending'
        ]);

        ProcessSubmission::dispatch($submission);

        return response()->json([
            'message' => 'Submission received',
            'submission_id' => $submission->id
        ]);
    }

    public function submitContest(Contest $contest,SubmitContestRequest $request)
    {
        $validated = $request->validated();
        $user=Auth::user();
        Gate::authorize('submit', $contest);
//        if ($user->contests()->where('id',$contest->id)->exists()) {
//            return response()->json([
//                'status' => true,
//                'message' => 'You already submitted this contest'
//            ], 403);
//        }


        $correct=0;
        $answers=$validated['answers'];
        $questions=$contest->questions()->get();
        foreach($questions as $question){
            $correctOption = $question->correctOption();
            if (isset($answers[$question->id]) && $correctOption && $answers[$question->id] == $correctOption->id) {
                $correct++;
            }
        }
        db::table('contest_user')->insert([
           'end_time' => now() // dateTime in database
            ,'correct_answers' => $correct,
            'user_id' => Auth::user()->id,
            'contest_id' =>$contest->id,
            //todo
            
            'gained_points' => 0,
            'rank' => 0
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Contest submitted successfully',
            'correct_answers' => $questions->count() >0 ? ($correct / $questions->count()) *100 : 0
        ]);

    }
}
