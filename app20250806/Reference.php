<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Reference
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $active
 * @property string $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reference query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reference whereUserId($value)
 * @mixin \Eloquent
 */
class Reference extends Model
{
    use LogModelEvent;

    protected $table = 'my_references';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'sort',
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
