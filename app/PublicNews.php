<?php

namespace App;

use App\Traits\CountableTrait;
use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\PublicNews
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $content
 * @property int $active
 * @property string|null $sort 分類
 * @property int $position 排序位置
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \App\Counter|null $counter
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read int $counter_count
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews query()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicNews whereUserId($value)
 * @mixin \Eloquent
 */
class PublicNews extends Model
{
    use SortableTrait;
    use LogModelEvent;
    use CountableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'sort',
        'active',
        'position',
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
