<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\RootTopic
 *
 * @property int $id
 * @property string $work_type
 * @property string $year
 * @property string $title
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\SampleReport[] $sampleReports
 * @property-read int|null $sample_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Topic[] $topics
 * @property-read int|null $topics_count
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic query()
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic whereWorkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RootTopic whereYear($value)
 * @mixin \Eloquent
 */
class RootTopic extends Model
{
    use LogModelEvent;

    public $timestamps = false;
    protected $fillable = [
        'title',
        'year',
        'work_type',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class, 'category');
    }

    //FIXME: 一對多誤用成hasOne，修正成hasMany會影響到reports.show和reports.submit的$item->reports->files
}
