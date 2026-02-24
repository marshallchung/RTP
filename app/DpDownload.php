<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\DpDownload
 *
 * @property int $id
 * @property int $user_id
 * @property string $category
 * @property string $title
 * @property string $content
 * @property int $active
 * @property int $position 排序位置
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDownload whereUserId($value)
 * @mixin \Eloquent
 */
class DpDownload extends Model
{
    use SortableTrait;
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'content',
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
