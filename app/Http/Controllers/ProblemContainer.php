<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemContainer extends Controller
{
    public function show(Contest $contest,Problem $problem)
    {
        $problem=$contest->problems()->find($problem->id);
        return response()->json([
            'status'=>true,
            'problem'=>$problem,
        ]);
    }

}
