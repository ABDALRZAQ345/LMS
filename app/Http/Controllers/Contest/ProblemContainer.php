<?php

namespace App\Http\Controllers\Contest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\SubmitProblemRequest;
use App\Jobs\ProcessSubmission;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProblemContainer extends Controller
{
    public function show(Contest $contest, Problem $problem): JsonResponse
    {

        $problem = $contest->problems()->findOrFail($problem->id);

        return response()->json([
            'status' => true,
            'problem' => $problem,
        ]);
    }
}
