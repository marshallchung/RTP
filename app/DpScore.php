<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpScore
 *
 * @property int $id
 * @property int $dp_course_id
 * @property int $dp_student_id
 * @property float $score
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \App\DpCourse|null $dpCourse
 * @property-read \App\DpStudent|null $dpStudent
 * @property-read \App\DpWaiver|null $dpWaiver
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereDpCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereDpStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpScore whereUserId($value)
 * @mixin \Eloquent
 */
class DpScore extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'score',
        'dp_course_id',
        'dp_student_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dpWaiver()
    {
        return $this->hasOne(DpWaiver::class);
    }

    public function dpStudent()
    {
        return $this->belongsTo(DpStudent::class);
    }

    public function dpCourse()
    {
        return $this->belongsTo(DpCourse::class);
    }
}
