<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Topic
 *
 * @property int $id
 * @property string $work_type
 * @property string $title
 * @property string $levels
 * @property string $type
 * @property string $class
 * @property string|null $exclude
 * @property int $category
 * @property int $unit_id
 * @property int $user_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CentralReport[] $centralReportCollection
 * @property-read int|null $central_report_collection_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CentralReport[] $centralReports
 * @property-read int|null $central_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Report[] $reportCollection
 * @property-read int|null $report_collection_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \App\RootTopic|null $rootTopic
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SeasonalReport[] $seasonalReportCollection
 * @property-read int|null $seasonal_report_collection_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SeasonalReport[] $seasonalReports
 * @property-read int|null $seasonal_reports_count
 * @property-read \App\User|null $unit
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereExclude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereLevels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Topic whereWorkType($value)
 * @mixin \Eloquent
 */
class Topic extends Model
{
    use LogModelEvent;

    public $timestamps = false;
    protected $fillable = [
        'type',
        'title',
        'levels',
        'type',
        'class',
        'exclude',
        'category',
        'unit_id',
        'user_id',
        'work_type',
        'created_at',
        'updated_at',
    ];

    //FIXME: 一對多誤用成hasOne，修正成hasMany會影響到reports.show和reports.submit的$item->reports->files
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function reportCollection()
    {
        return $this->hasMany(Report::class);
    }

    public function seasonalReports()
    {
        return $this->hasMany(SeasonalReport::class);
    }

    public function seasonalReportCollection()
    {
        return $this->hasMany(SeasonalReport::class);
    }

    public function centralReports()
    {
        return $this->hasMany(CentralReport::class);
    }

    public function centralReportCollection()
    {
        return $this->hasMany(CentralReport::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(User::class, 'unit_id');
    }

    public function rootTopic()
    {
        return $this->belongsTo(RootTopic::class, 'category');
    }

    public function sampleReports()
    {
        return $this->hasMany(SampleReport::class);
    }
}
