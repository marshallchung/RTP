<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpStudent
 *
 * @property int $id
 * @property int $user_id
 * @property int $county_id
 * @property string $username
 * @property string $password
 * @property string $gender
 * @property string $TID
 * @property string $name
 * @property string|null $certificate 證書編號
 * @property string $birth
 * @property string|null $field
 * @property string|null $education 最高學歷
 * @property string|null $service 服務單位
 * @property string|null $title 職稱
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property string $address
 * @property string|null $addressId 地址識別碼
 * @property string|null $community
 * @property string $unit_first_course
 * @property string|null $date_first_finish
 * @property string|null $unit_second_course
 * @property string|null $date_second_finish
 * @property string|null $Multiple 多元化防災士
 * @property string|null $plan 培訓計畫名稱
 * @property int|null $score_academic 學科測驗成績
 * @property int $physical_pass 術科測驗成績是否合格
 * @property int $pass 認證結果是否合格
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $last_login_at
 * @property int $active
 * @property int $advance
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \App\User|null $county
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpCourse[] $dpCourses
 * @property-read int|null $dp_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpExperience[] $dpExperiences
 * @property-read int|null $dp_experiences_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpScore[] $dpScores
 * @property-read int|null $dp_scores_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpStudentSubject[] $dpStudentSubjects
 * @property-read int|null $dp_student_subjects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpWaiver[] $dpWaivers
 * @property-read int|null $dp_waivers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereAdvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereCertificate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereCommunity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereDateFirstFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereDateSecondFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereMultiple($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent wherePass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent wherePhysicalPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereScoreAcademic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereTID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereUnitFirstCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereUnitSecondCourse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpStudent whereUsername($value)
 * @mixin \Eloquent
 */
class DpStudent extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'county_id',
        'TID',
        'username',
        'gender',
        'name',
        'certificate',
        'birth',
        'field',
        'education',
        'service',
        'title',
        'email',
        'phone',
        'mobile',
        'residence_county',
        'township',
        'address',
        'addressId',
        'household_county',
        'household_township',
        'household_address',
        'community',
        'unit_first_course',
        'date_first_finish',
        'unit_second_course',
        'date_second_finish',
        'plan',
        'score_academic',
        'physical_pass',
        'pass',
        'password',
        'change_default',
        'next_change',
        'willingness',
        'active',
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

    public function dpCourses()
    {
        return $this->belongsToMany(DpCourse::class, 'dp_scores')->withTimestamps()->withPivot('score', 'user_id');
    }

    public function dpScores()
    {
        return $this->hasMany(DpScore::class);
    }

    public function dpWaivers()
    {
        return $this->hasManyThrough(DpWaiver::class, DpScore::class);
    }

    public function dpExperiences()
    {
        return $this->hasMany(DpExperience::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }

    public function getCourseSubjects($student_id = null)
    {
        $sql = "dp_subjects.id,dp_subjects.name,dp_advance_course_subjects.hour,dp_advance_course_subjects.start_date";
        $course_subjects = DpSubject::selectRaw($sql)
            ->leftJoin('dp_advance_student_subjects', function ($join) use ($student_id) {
                $join->on('dp_advance_student_subjects.dp_course_subject_id', '=', 'dp_subjects.id')
                    ->where('dp_advance_student_subjects.dp_student_id', '=', $student_id);
            })
            ->leftJoin('dp_advance_course_subjects', 'dp_advance_course_subjects.id', '=', 'dp_advance_student_subjects.dp_advance_course_subjects')
            ->where('dp_subjects.advance', true)
            ->orderBy('dp_subjects.position', 'asc')->get();
        return $course_subjects ? $course_subjects->toArray() : [];
    }

    public function dpAdvanceSubjects()
    {
        return $this->belongsToMany(DpSubject::class, 'dp_advance_student_subjects', 'dp_student_id', 'dp_course_subject_id');
    }

    public function dpStudentSubjects()
    {
        return $this->hasMany(DpStudentSubject::class);
    }
}
