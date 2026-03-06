<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ImageDatumType
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ImageDatum[] $imageData
 * @property-read int|null $image_data_count
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatumType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ImageDatumType extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'name',
    ];

    public function imageData()
    {
        return $this->hasMany(ImageDatum::class);
    }
}
