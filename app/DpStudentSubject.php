<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpStudentSubject
 *
 * @property int $id
 * @property int $dp_student_id
 * @property int $dp_subject_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\DpStudent $dpStudent
 * @property-read \App\DpSubject $dpSubject
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject whereDpStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject whereDpSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudentSubject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DpStudentSubject extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'dp_student_id',
        'dp_subject_id',
        'type',
    ];

    public function dpStudent()
    {
        return $this->belongsTo(DpStudent::class);
    }

    public function dpSubject()
    {
        return $this->belongsTo(DpSubject::class);
    }
}
