<?php

namespace App\Http\Requests\Comments;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],

            'comment_id' => [
                'bail',
                'nullable',
                Rule::exists('comments', 'id'),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $parentId = $this->input('comment_id');
            if (!$parentId) {
                return;
            }

            $parent = Comment::select('id', 'comment_id', 'video_id')->find($parentId);

            if (!$parent) {
                $v->errors()->add('comment_id', 'The parent comment does not exist.');
                return;
            }

            if (!is_null($parent->comment_id)) {
                $v->errors()->add('comment_id', 'You cannot reply to a reply. Only root comments can have replies.');
                return;
            }

            $video = $this->route('video');
            if ($video && (int) $parent->video_id !== (int) $video->id) {
                $v->errors()->add('comment_id', 'The parent comment does not belong to this video.');
                return;
            }

            $alreadyHasReply = Comment::where('comment_id', $parentId)->exists();
            if ($alreadyHasReply) {
                $v->errors()->add('comment_id', 'This comment already has a reply.');
            }
        });
    }
}
