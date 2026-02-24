<?php

namespace App;

use App\Traits\CountableTrait;
use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Qa
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property bool $active
 * @property bool $publish
 * @property string $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \App\Counter|null $counter
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read int $counter_count
 * @method static \Illuminate\Database\Eloquent\Builder|Qa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Qa query()
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa wherePublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Qa whereUserId($value)
 * @mixin \Eloquent
 */
class Qa extends Model
{
    use LogModelEvent;
    use CountableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'active',
        'publish',
        'sort',
    ];

    protected $casts = [
        'active'  => 'boolean',
        'publish' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
