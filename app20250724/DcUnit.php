<?php

namespace App;

use App\Traits\LogModelEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DcUnit
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int|null $population
 * @property int $county_id
 * @property string|null $location
 * @property int $is_experienced
 * @property string|null $environment
 * @property string|null $risk
 * @property string|null $pattern
 * @property string $manager
 * @property string $phone
 * @property string|null $email
 * @property string $manager_position
 * @property string $manager_address
 * @property string|null $dp_name 防災士姓名
 * @property string|null $dp_phone 防災士電話
 * @property string|null $rank 星等
 * @property string|null $rank_started_date 星等生效日期
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \App\User|null $county
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DcCertification[] $dcCertifications
 * @property-read int|null $dc_certifications_count
 * @property-read \App\DcUser|null $dcUser
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $filesOfLocation
 * @property-read int|null $files_of_location_count
 * @property-read bool|null $is_close_to_expired_date
 * @property-read bool|null $is_expired
 * @property-read string|null $rank_expired_date
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit closeToExpiredDateOrExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereDpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereDpPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereEnvironment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereIsExperienced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereManagerAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereManagerPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit wherePattern($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereRankStartedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereRisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUnit whereUserId($value)
 * @mixin \Eloquent
 */
class DcUnit extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'name',
        'population',
        'county_id',
        'location',
        'is_experienced',
        'environment',
        'risk',
        'pattern',
        'manager',
        'phone',
        'email',
        'manager_position',
        'manager_address',
        'dp_name',
        'dp_phone',
        'rank',
        'rank_started_date',
        'active',
        'within_plan',
        'native',
        'date_extension',
        'extension_date',
        'rank_year',
        'township',
        'village',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function county()
    {
        return $this->belongsTo(User::class, 'county_id');
    }

    public function dcCertifications()
    {
        return $this->hasMany(DcCertification::class);
    }

    public function dcUser()
    {
        return $this->hasOne(DcUser::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }

    public function filesOfLocation()
    {
        return $this->morphMany(File::class, 'post')->where('memo', 'dc-location');
    }

    /**
     * @return string|null
     */
    public function getRankExpiredDateAttribute()
    {
        if (!$this->rank_started_date) {
            return null;
        }
        $rank_started_date = $this->rank_started_date;
        $year = $this->rank_year;
        if ($this->date_extension) {
            if ($this->extension_date !== null) {
                $rank_started_date = $this->extension_date;
                $year = 3;
            } else {
                $year += 3;
            }
        }
        return (new Carbon($rank_started_date))->addYears($year)->format('Y-m-d');
    }

    /**
     * @return bool|null
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->rank_started_date) {
            return null;
        }
        $rank_started_date = $this->rank_started_date;
        $year = $this->rank == '三星' ? 5 : 3;
        if ($this->date_extension) {
            if ($this->extension_date !== null) {
                $rank_started_date = $this->extension_date;
                $year = 3;
            } else {
                $year += 3;
            }
        }
        return (new Carbon($rank_started_date))->addYears($year)->lessThan(now());
    }

    /**
     * @return bool|null
     */
    public function getIsCloseToExpiredDateAttribute()
    {
        if (!$this->rank_started_date) {
            return null;
        }
        $rank_started_date = $this->rank_started_date;
        $year = $this->rank == '三星' ? 5 : 3;
        if ($this->date_extension) {
            if ($this->extension_date !== null) {
                $rank_started_date = $this->extension_date;
                $year = 3;
            } else {
                $year += 3;
            }
        }
        return (new Carbon($rank_started_date))->addYears($year)->subMonths(1)->lessThan(now());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCloseToExpiredDateOrExpired($query)
    {
        $dateOfThreshold = Carbon::now()->subYears(2)->addMonths(1);

        return $query->whereNotNull('rank_started_date')->whereDate('rank_started_date', '<', $dateOfThreshold);
    }
}
