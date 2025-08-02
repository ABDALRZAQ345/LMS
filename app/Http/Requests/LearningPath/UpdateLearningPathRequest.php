<?php

namespace App\Http\Requests\LearningPath;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLearningPathRequest extends FormRequest
{

    public function authorize(): bool
    {
        return \Gate::allows('update',$this->route('learningPath'));
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'image' => ['nullable', 'image'],
            'courses' => ['sometimes', 'array', 'min:1'],
            'courses.*' => ['sometimes', 'exists:courses,id', 'distinct'],
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
                    ->where('request_status', '!=', 'accepted')
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
                                $q->where('verified', '!=', 1)
                                    ->where('request_status', '!=', 'accepted');
                            });
                    })
                    ->pluck('id')
                    ->toArray();

                if (!empty($invalidCourses)) {
                    $validator->errors()->add(
                        'courses',
                        'You can only add your own accepted courses. Invalid course IDs: ' . implode(', ', $invalidCourses)
                    );
                }
            }
        });
    }

}
