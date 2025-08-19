<?php

namespace App\Services\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Courses\AdminRequestCoursesResource;
use App\Jobs\SendFirebaseNotification;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Courses\AdminCourseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminCourseService
{
    protected $adminCourseRepo;
    public function __construct(AdminCourseRepository $adminCourseRepo)
    {
        $this->adminCourseRepo = $adminCourseRepo;
    }

    public function requestsCourses($validated){
        $courses = $this->adminCourseRepo->requestsCourses($validated);
        $data = [
            'courses' => AdminRequestCoursesResource::collection($courses),
            'meta' => getMeta($courses),
        ];

        return ResponseHelper::jsonResponse($data,'Get Requests Courses Successfully');
    }

    public function UpdateCourseRequestStatus($course,string $status)
    {
        if ($status == 'accepted') {
            return $course->update([
                'request_status' => $status,
                'verified' => 1,
            ]);
        }else{
            return $course->update([
                'request_status' => $status,
                'verified' => 0,
            ]);
        }
    }

    public function deleteCourse(Course $course): void
    {
        if ((float) $course->price === 0.0) {
            $course->delete(); // علاقات FK مع cascadeOnDelete ستحذف المحتوى
            return;
        }

        DB::transaction(function () use ($course) {
            $lockedCourse = Course::query()
                ->whereKey($course->id)
                ->lockForUpdate()
                ->firstOrFail();

            $paidEnrollments = $lockedCourse->students()
                ->wherePivot('paid', '>', 0)
                ->select('users.id', 'users.balance') // رصيد الطالب
                ->withPivot('paid')                   // المبلغ المدفوع للكورس
                ->lockForUpdate()
                ->get();

            $totalPaid = $paidEnrollments->sum(fn ($u) => (float) $u->pivot->paid);

            if ($totalPaid <= 0) {
                $lockedCourse->delete();
                return;
            }

            $teacher = User::query()
                ->whereKey($lockedCourse->user_id)
                ->lockForUpdate()
                ->firstOrFail();

            $admin = User::query()
                ->where('role', 'admin')
                ->lockForUpdate()
                ->first();

            if (!$admin) {
                throw ValidationException::withMessages([
                    'admin' => ['No admin user found to fund refunds.'],
                ]);
            }

            $adminShare   = round($totalPaid * 0.40, 2);
            $teacherShare = round($totalPaid * 0.60, 2);

            $admin->balance   = round(((float)$admin->balance)   - $adminShare, 2);
            $teacher->balance = round(((float)$teacher->balance) - $teacherShare, 2);
            $admin->save();
            $teacher->save();

            foreach ($paidEnrollments as $student) {
                $refund = (float) $student->pivot->paid;
                if ($refund > 0) {
                    $student->balance = round(((float)$student->balance) + $refund, 2);
                    $student->save();

                    $title = 'Course Deleted';
                    $body  = "The course '{$lockedCourse->title}' has been deleted. "
                        . "The amount you paid ({$refund}) has been refunded to your balance.";

                    SendFirebaseNotification::dispatch($student, $title, $body);
                }
            }

            $lockedCourse->delete();
        });
    }
}
