<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\NewsType
 *
 * @property int $id
 * @property string|null $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\News[] $news
 * @property-read int|null $news_count
 * @method static \Illuminate\Database\Eloquent\Builder|NewsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsType query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsType whereName($value)
 * @mixin \Eloquent
 */
class NewsType extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'name',
    ];

    public static function getOptions()
    {
        $options = [null => ' - 請下拉選擇 - '] + self::pluck('name', 'id')->toArray();

        return $options;
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
