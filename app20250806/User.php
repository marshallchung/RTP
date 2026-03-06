<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laratrust\Traits\HasRolesAndPermissions;
use Laratrust\Traits\UserOwnsThing;
use Laratrust\Contracts\LaratrustUser;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string|null $type
 * @property int $origin_role
 * @property int|null $level
 * @property int|null $class
 * @property int|null $area
 * @property int|null $county_id
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $sort_order
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read User|null $county
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DcDownload[] $dcDownload
 * @property-read int|null $dc_download_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DcSchedule[] $dcSchedules
 * @property-read int|null $dc_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DcUnit[] $dcUnits
 * @property-read int|null $dc_units_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $districts
 * @property-read int|null $districts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpCourse[] $dpCourses
 * @property-read int|null $dp_courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpDownload[] $dpDownload
 * @property-read int|null $dp_download_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpResource[] $dpResource
 * @property-read int|null $dp_resource_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpScore[] $dpScores
 * @property-read int|null $dp_scores_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpStudent[] $dpStudents
 * @property-read int|null $dp_students_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpTeacher[] $dpTeachers
 * @property-read int|null $dp_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpTrainingInstitution[] $dpTrainingInstitution
 * @property-read int|null $dp_training_institution_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpWaiver[] $dpWaivers
 * @property-read int|null $dp_waivers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FrontDownload[] $frontDownload
 * @property-read int|null $front_download_count
 * @property-read bool $can_change_identity
 * @property-read array $changeable_identities
 * @property-read string $county_name
 * @property-read mixed $details
 * @property-read mixed $full_county_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Guidance[] $guidance
 * @property-read int|null $guidance_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ImageDatum[] $imageData
 * @property-read int|null $image_data_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Introduction[] $introductions
 * @property-read int|null $introductions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\News[] $news
 * @property-read int|null $news_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Plan[] $plans
 * @property-read int|null $plans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpResult[] $dp_result
 * @property-read int|null $dp_result_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpResult[] $dp_result
 * @property-read int|null $dp_result_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Presentation[] $presentation
 * @property-read int|null $presentation_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PublicNews[] $publicNews
 * @property-read int|null $public_news_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Qa[] $qas
 * @property-read int|null $qas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Questionnaire[] $questionnaires
 * @property-read int|null $questionnaires_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reference[] $references
 * @property-read int|null $references_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SignLocation[] $signLocations
 * @property-read int|null $sign_locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StaticPage[] $staticPages
 * @property-read int|null $static_pages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Topic[] $topics
 * @property-read int|null $topics_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Upload[] $uploads
 * @property-read int|null $uploads_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserAlias[] $userAliases
 * @property-read int|null $user_aliases_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $video
 * @property-read int|null $video_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User orWherePermissionIs($permission = '')
 * @method static \Illuminate\Database\Eloquent\Builder|User orWhereRoleIs($role = '', $team = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDoesntHavePermission()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDoesntHaveRole()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOriginRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissionIs($permission = '', $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleIs($role = '', $team = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, LaratrustUser
{
    use Authenticatable, CanResetPassword, HasRolesAndPermissions;
    use LogModelEvent;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'change_default', 'next_change',];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'county_name',
    ];

    public function getDetailsAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Checks if the user owns the thing.
     *
     * @param  Object  $thing
     * @param  string  $foreignKeyName
     * @return boolean
     */
    public function owns($thing)
    {
        return $thing['user_id'] == $this->id;
    }

    /**
     * 縣市名稱
     *
     * @return string
     */
    public function getCountyNameAttribute()
    {
        if ($this->county) {
            return $this->county->name;
        }

        return $this->name;
    }

    /**
     * @return bool
     */
    public function getCanChangeIdentityAttribute()
    {
        $originIdentity = session('origin_identity');
        /** @var User $originIdentityUser */
        $originIdentityUser = User::find($originIdentity);
        if ($originIdentityUser) {
            //已經是模擬他人身分
            return true;
        }
        if (!$this->type) {
            //管理員
            return true;
        }
        if ($this->type == 'county') {
            //縣市
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getChangeableIdentitiesAttribute()
    {
        if ($this->type && $this->type != 'civil') {
            return [];
        }
        $userQuery = User::with('county')->where('id', '<>', $this->id);
        if ($this->type == 'civil') {
            //社團法人臺灣防災教育訓練學會
            $userQuery->where('type', 'dp-training');
        }
        /** @var Collection|User[] $users */
        $users = $userQuery->get();
        $changeableIdentities = [];
        foreach ($users as $user) {
            if ($user->type == 'district') {
                $name = $user->county->name . ' - ' . $user->name;
            } else {
                $name = $user->name;
            }
            $changeableIdentities[$user->id] = $name;
        }

        return $changeableIdentities;
    }

    public function video()
    {
        return $this->hasMany(Video::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function publicNews()
    {
        return $this->hasMany(PublicNews::class);
    }

    public function qas()
    {
        return $this->hasMany(Qa::class);
    }

    public function guidance()
    {
        return $this->hasMany(Guidance::class);
    }

    public function dpTeachers()
    {
        return $this->hasMany(DpTeacher::class);
    }

    public function dpCourses()
    {
        return $this->hasMany(DpCourse::class);
    }

    public function dpScores()
    {
        return $this->hasMany(DpScore::class);
    }

    public function dpStudents()
    {
        return $this->hasMany(DpStudent::class);
    }

    public function dpWaivers()
    {
        return $this->hasMany(DpWaiver::class);
    }

    public function dpResource()
    {
        return $this->hasMany(DpResource::class);
    }

    public function dpTrainingInstitution()
    {
        return $this->hasMany(DpTrainingInstitution::class);
    }

    public function dcSchedules()
    {
        return $this->hasMany(DcSchedule::class);
    }

    public function dcUnits()
    {
        return $this->hasMany(DcUnit::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function firstPlans()
    {
        return $this->hasOne(Plan::class)->oldestOfMany();
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function dpResult()
    {
        return $this->hasMany(DpResult::class);
    }

    public function dpDocument()
    {
        return $this->hasMany(DpDocument::class);
    }

    public function presentation()
    {
        return $this->hasMany(Presentation::class, 'user_id', 'id');
    }

    public function introductions()
    {
        return $this->hasMany(Introduction::class);
    }

    public function imageData()
    {
        return $this->hasMany(ImageDatum::class);
    }

    public function signLocations()
    {
        return $this->hasMany(SignLocation::class);
    }

    public function county()
    {
        return $this->belongsTo(User::class, 'county_id');
    }

    public function districts()
    {
        return $this->hasMany(User::class, 'county_id');
    }

    public function hasPermOfUser($targetUser)
    {
        return is_null($this->type) || $this->origin_role == 6 || $this->id == $targetUser->id || $this->id == $targetUser->county_id;
    }

    public function frontDownload()
    {
        return $this->hasMany(FrontDownload::class);
    }

    public function dcDownload()
    {
        return $this->hasMany(DcDownload::class);
    }

    public function dpDownload()
    {
        return $this->hasMany(DpDownload::class);
    }

    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class)
            ->withPivot('id', 'status', 'answers', 'comments')->withTimestamps();
    }

    public function userAliases()
    {
        return $this->hasMany(UserAlias::class);
    }

    //public function role()
    //{
    //    return $this->hasOne('\App\Role');
    //}

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function staticPages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StaticPage::class);
    }

    public function getFullCountyNameAttribute()
    {
        $fullCountyName = $this->name;
        if ($this->county) {
            $fullCountyName = $this->county->name . ' - ' . $fullCountyName;
        }

        return $fullCountyName;
    }
}
