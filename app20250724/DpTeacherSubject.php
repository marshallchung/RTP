<?php

namespace App;

use App\Traits\LogModelEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpTeacherSubject
 *
 * @property int $id
 * @property int $dp_teacher_id
 * @property int $dp_subject_id
 * @property string|null $type 師資類型
 * @property string|null $pass_date 通過日期
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\DpSubject $dpSubject
 * @property-read \App\DpTeacher $dpTeacher
 * @property-read bool $is_expired
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject whereDpSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject whereDpTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject wherePassDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacherSubject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DpTeacherSubject extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'dp_teacher_id',
        'dp_subject_id',
        'type',
        'pass_date',
    ];

    public function dpTeacher()
    {
        return $this->belongsTo(DpTeacher::class);
    }

    public function dpSubject()
    {
        return $this->belongsTo(DpSubject::class);
    }

    /**
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        if ($this->type !== '種子師資') {
            return false;
        }
        if (!$this->pass_date) {
            return true;
        }
        try {
            $passDateCarbon = new Carbon($this->pass_date);
            $expiredDate = $passDateCarbon->addYears(3);

            return $expiredDate->isPast();
        } catch (\Exception $e) {
            return true;
        }
    }
}
