<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\DpSubject
 *
 * @property int $id
 * @property string $name 科目名稱
 * @property int $position 排序位置
 * @property boolean $advance 進階消防士課程
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpStudentSubject[] $dpStudentSubjects
 * @property-read int|null $dp_student_subjects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpTeacherSubject[] $dpTeacherSubjects
 * @property-read int|null $dp_teacher_subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpSubject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DpSubject extends Model
{
    use SortableTrait;
    use LogModelEvent;

    protected $fillable = [
        'position',
        'name',
        'advance',
    ];

    public function dpTeacherSubjects()
    {
        return $this->hasMany(DpTeacherSubject::class);
    }

    public function dpStudentSubjects()
    {
        return $this->hasMany(DpStudentSubject::class)
            ->join('dp_subjects', 'dp_subjects.id', '=', 'dp_advance_student_subjects.dp_subject_id')
            ->orderBy('dp_subjects.position');
    }

    public function dpAdvanceStudentSubjects()
    {
        return $this->hasMany(DpAdvanceStudentSubject::class)
            ->join('dp_subjects', 'dp_subjects.id', '=', 'dp_student_subjects.dp_course_subject_id')
            ->orderBy('dp_subjects.position');
    }

    public function dpAdvanceCourses()
    {
        return $this->belongsToMany(DpCourse::class, 'dp_advance_course_subjects', 'dp_course_subject_id', 'dp_course_id')->withPivot('id', 'hour', 'start_date');
    }

    public function dpAdvanceStudent()
    {
        return $this->belongsToMany(DpStudent::class, 'dp_advance_student_subjects', 'dp_course_subject_id', 'dp_student_id');
    }
}
