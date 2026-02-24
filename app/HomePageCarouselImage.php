<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\HomePageCarouselImage
 *
 * @property int $id
 * @property int $position 排序位置
 * @property string $title 標題
 * @property string|null $url 連結網址
 * @property bool $active 上線
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HomePageCarouselImage whereUrl($value)
 * @mixin \Eloquent
 */
class HomePageCarouselImage extends Model
{
    use SortableTrait;
    use LogModelEvent;

    protected $fillable = [
        'position',
        'title',
        'url',
        'active',
    ];

    protected $casts = [
        'active' => 'bool',
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
