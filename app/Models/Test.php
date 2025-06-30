<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): MorphMany
    {
        return $this->morphMany(Question::class, 'questionable');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'test_user')
            ->withPivot('start_time', 'end_time', 'correct_answers','updated_at');
    }

    //todo use it in other get percentage
    public function getPercentageOfStudent($userId)
    {

        $correct_answers=db::table('test_user')->where('user_id',$userId)
            ->where('test_id',$this->id)->max('correct_answers');;

        return getPercentage($correct_answers,$this->questions()->count(),true);
    }
}
