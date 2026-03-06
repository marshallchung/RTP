<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\IntroductionType
 *
 * @property int $id
 * @property string $name 分類名稱
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Introduction[] $introductions
 * @property-read int|null $introductions_count
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IntroductionType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IntroductionType extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'name',
    ];

    public function introductions()
    {
        return $this->hasMany(Introduction::class);
    }
}
