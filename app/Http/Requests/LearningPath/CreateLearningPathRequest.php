<?php

namespace App\Http\Requests\LearningPath;

use App\Models\Course;
use App\Models\LearningPath;
use Illuminate\Foundation\Http\FormRequest;

class CreateLearningPathRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Gate::allows('create', LearningPath::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image'],
            'courses' => ['required', 'array', 'min:1'],
            'courses.*' => ['required', 'exists:courses,id', 'distinct'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courses = $this->input('courses', []);
            if (empty($courses)) return;

            $user = auth()->user();

            if ($user->role === 'admin') {
                $invalidCourses = Course::whereIn('id', $courses)
                    ->where('verified', '!=', 1)
                    ->pluck('id')
                    ->toArray();

                if (!empty($invalidCourses)) {
                    $validator->errors()->add(
                        'courses',
                        'The following courses are not accepted: ' . implode(', ', $invalidCourses)
                    );
                }

            } elseif ($user->role === 'teacher') {
                $invalidCourses = Course::whereIn('id', $courses)
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', '!=', $user->id)
                            ->orWhere(function ($q) {
                                $q->where('verified', '!=', 1);
                            });
                    })
                    ->pluck('id')
                    ->toArray();

                if (!empty($invalidCourses)) {
                    $validator->errors()->add(
                        'courses',
                        'You can only add your own **accepted** courses. Invalid course IDs: ' . implode(', ', $invalidCourses)
                    );
                }
            }
        });
    }


}
