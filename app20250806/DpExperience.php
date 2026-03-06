<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpExperience
 *
 * @property int $id
 * @property int $dp_student_id
 * @property string|null $unit
 * @property string|null $document_code
 * @property string $name
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\DpStudent|null $dpStudent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereDocumentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereDpStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpExperience whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DpExperience extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'unit',
        'document_code',
        'name',
        'date',
        'work_hours',
    ];

    public function dpStudent()
    {
        return $this->belongsTo(DpStudent::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
