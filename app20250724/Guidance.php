<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Guidance
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guidance whereUserId($value)
 * @mixin \Eloquent
 */
class Guidance extends Model
{
    use LogModelEvent;

    protected $table = 'guidance';
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'active',
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
