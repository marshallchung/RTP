<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\DcDownload
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
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload query()
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcDownload whereUserId($value)
 * @mixin \Eloquent
 */
class DcDownload extends Model
{
    use LogModelEvent;
    use SortableTrait;

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
