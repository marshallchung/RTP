<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\SampleReport
 *
 * @property int $id
 * @property int $user_id
 * @property int $root_topic_id
 * @property bool $is_sample
 * @property string|null $memo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\RootTopic $rootTopic
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereIsSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereRootTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SampleReport whereUserId($value)
 * @mixin \Eloquent
 */
class SampleReport extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'topic_id',
        'is_sample',
        'memo',
    ];

    protected $casts = [
        'is_sample' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
