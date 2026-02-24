<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpCivil
 *
 * @property int $id
 * @property string $name 名稱
 * @property string|null $phone 連絡電話
 * @property string|null $address 機構地址
 * @property string|null $front_man 代表人
 * @property string|null $business 辦理業務
 * @property string|null $url 網址
 * @property int $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereBusiness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereFrontMan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpCivil whereUrl($value)
 * @mixin \Eloquent
 */
class DpCivil extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'front_man',
        'business',
        'url',
        'active',
    ];
}
