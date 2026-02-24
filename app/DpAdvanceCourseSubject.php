<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\DpAdvanceCourseSubject
 *
 * @property int $id
 * @property int $dp_course_id
 * @property int $dp_course_subject_id
 * @property int $hour
 * @property string|null $start_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @mixin \Eloquent
 */
class DpAdvanceCourseSubject extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'dp_course_id',
        'dp_course_subject_id',
        'hour',
        'start_date',
    ];
}
