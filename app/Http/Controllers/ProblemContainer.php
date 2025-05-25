<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Problem;

class ProblemContainer extends Controller
{
    public function show(Contest $contest, Problem $problem): \Illuminate\Http\JsonResponse
    {
        $problem = $contest->problems()->findOrFail($problem->id);

        return response()->json([
            'status' => true,
            'problem' => $problem,
        ]);
    }
}
