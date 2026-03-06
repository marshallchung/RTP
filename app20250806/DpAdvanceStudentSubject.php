<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\DpAdvanceStudentSubject
 *
 * @property int $id
 * @property int $dp_student_id
 * @property int $dp_course_subject_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent
 */
class DpAdvanceStudentSubject extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'dp_student_id',
        'dp_course_subject_id',
        'dp_advance_course_subjects',
    ];
}
