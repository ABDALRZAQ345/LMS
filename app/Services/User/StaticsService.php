<?php

namespace App\Services\User;

use App\Http\Resources\Users\UserResource;
use App\Models\Contest;
use App\Models\Course;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaticsService
{
    public function overview()
    {


      return   \Cache::remember('admin.overview',60*60,function (){
            $total_students = User::where('role','student')->count();
            $active_students = User::where('role','student')->where('last_online', '>=', now()->subMinutes(10))->count();
            $total_teachers=User::where('role','teacher')->count();
            $active_teachers = User::where('role','teacher')->where('last_online', '>=', now()->subMinutes(10))->count();
            $total_courses=Course::where('verified',true)->count();
            $total_contests=Contest::where('request_status','accepted')->count();
            $upcoming_contests=Contest::where('request_status','accepted')->where('status','coming')->count();
            $active_contests=Contest::where('request_status','accepted')->where('status','active')->count();
            $ended_contests=Contest::where('request_status','accepted')->where('status','ended')->count();
            $total_revenue=Db::table('course_user')->sum('paid');
            return response()->json([
                'total_students' => $total_students,
                'active_students' => $active_students,
                'total_teachers' => $total_teachers,
                'active_teachers' => $active_teachers,
                'total_courses' => $total_courses,
                'total_contests' => $total_contests,
                'upcoming_contests' => $upcoming_contests,
                'active_contests' => $active_contests,
                'ended_contests' => $ended_contests,
                'total_revenue' => $total_revenue,
            ]);
        });

    }

    public function StudentsStatistics()
    {


    }

    public function StudentsLastWeek()
    {
        $startOfWeek = Carbon::now()->startOfWeek()->toDateTimeString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateTimeString();


        $query = User::where('role', 'student')
            ->where('email_verified', true)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DAYOFWEEK(created_at) as day, COUNT(*) as count')
            ->groupByRaw('DAYOFWEEK(created_at)')
            ->pluck('count', 'day');

        $studentsLastWeek = [];

        for ($i = 0; $i <= 6; $i++) {
            $dayName = Carbon::now()->startOfWeek()->addDays($i )->format('l');
            $studentsLastWeek[$dayName] = $query->get($i+2, 0);
        }

        return response()->json([
           'studentsLastWeek' => $studentsLastWeek,
        ]);
    }

    public function StudentsPerMonth()
    {
        return \Cache::remember('admin.studentsPerMonth',60*60*24,function (){
            $currentYear = now()->year;
            $query = User::where('role', 'student')
                ->where('email_verified', true)
                ->whereYear('created_at', $currentYear)
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupByRaw('MONTH(created_at)')
                ->pluck('count', 'month');

            $studentPerMonth = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthName = Carbon::create()->month($i)->format('F');
                $studentPerMonth[$monthName] = $query->get($i, 0);
            }

            return
                response()->json([
                    'year' => $currentYear,
                    'studentsPerMonth'=>$studentPerMonth
                ]);

        });


    }

    public function ProjectsByType()
    {
        return \Cache::remember('admin.projectsByType',60*60*24,function (){

            $tags = DB::table('tags')
                ->selectRaw('tags.name, COUNT(projects.id) as count')
                ->leftJoin('projects', function($join) {
                    $join->on('projects.tag_id', '=', 'tags.id')
                        ->where('projects.status', '=', 'accepted');
                })
                ->groupBy('tags.name')
                ->pluck('count', 'tags.name');

        return
            response()->json([
               'projectsByType' => $tags
            ]);

        });

    }

    public function overviewBudget(){
        return Cache::remember('admin.overviewBudget', 60 * 60 * 24, function () {
            // جلب المدفوعات مجمعة حسب الشهر
            $payments = DB::table('course_user')
                ->selectRaw("MONTH(created_at) as month_num, SUM(paid) as revenue")
                ->where('paid', '>', 0)
                ->groupBy(DB::raw("MONTH(created_at)"))
                ->orderBy('month_num')
                ->get();


            $revenues = [];
            $expenses = [];

            for ($i = 1; $i <= 12; $i++) {
                $monthName = Carbon::create()->month($i)->format('F');
                $revenue = (float) $payments->get($i, 0);
                $revenues[$monthName] = $revenue;
                $expenses[$monthName] = round($revenue * 0.6, 2);
            }

            return  [
                'revenues' => $revenues,
                'expenses' => $expenses,
            ];
        });
    }
}
