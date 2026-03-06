<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpCourse
 *
 * @property int $id
 * @property int $user_id
 * @property int $county_id
 * @property string|null $organizer 主辦單位
 * @property string $name
 * @property string $content
 * @property string|null $contact_person 聯絡人
 * @property string $email
 * @property string $phone
 * @property string|null $url
 * @property string|null $date_from
 * @property string|null $date_to
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \App\User|null $county
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpScore[] $dpScores
 * @property-read int|null $dp_scores_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpStudent[] $dpStudents
 * @property-read int|null $dp_students_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereAdvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereOrganizer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCourse whereUserId($value)
 * @mixin \Eloquent
 */
class DpCourse extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'county_id',
        'organizer',
        'name',
        'content',
        'contact_person',
        'email',
        'phone',
        'url',
        'date_from',
        'date_to',
        'active',
        'stop_signup',
        'exclusive',
        'advance',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function county()
    {
        return $this->belongsTo(User::class, 'county_id');
    }

    public function dpStudents()
    {
        return $this->belongsToMany(DpStudent::class, 'dp_scores')->withTimestamps()->withPivot('score', 'user_id');
    }

    public function dpAdvanceSubjects()
    {
        return $this->belongsToMany(DpSubject::class, 'dp_advance_course_subjects', 'dp_course_id', 'dp_course_subject_id')->withPivot('id', 'hour', 'start_date');
    }

    public function dpScores()
    {
        return $this->hasMany(DpScore::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
