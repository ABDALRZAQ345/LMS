<?php

namespace App\Services;

use App\Models\Contest;
use App\Models\Problem;

class ProblemService
{


    public function getObjectSubmissions(Contest|Problem $object,$user_id='all',$language='all',$status='all',$items=30): \Illuminate\Pagination\LengthAwarePaginator
    {
        $submissions=$object->submissions()->with('problem');

        if($user_id==null || $user_id != 'all' ){
            $submissions = $submissions->where('user_id',$user_id);
        }
        if($language != 'all'){
            $submissions = $submissions->where('language',$language);
        }
        if($status != 'all'){
            $submissions = $submissions->where('status',$status);
        }
        return $submissions->paginate($items);
    }
}
