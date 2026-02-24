<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\DpTrainingInstitution
 *
 * @property int $id
 * @property string $name 名稱
 * @property int $county_id 縣市
 * @property string|null $phone 連絡電話
 * @property string|null $address 訓練地址
 * @property string|null $addressId 地址識別碼
 * @property string|null $url 官方網址
 * @property int $active
 * @property string|null $expired_date 有效期限
 * @property int $position 排序位置
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $county
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereCountyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTrainingInstitution whereUrl($value)
 * @mixin \Eloquent
 */
class DpTrainingInstitution extends Model
{
    use SortableTrait;
    use LogModelEvent;

    protected $fillable = [
        'name',
        'county_id',
        'phone',
        'address',
        'addressId',
        'url',
        'active',
        'expired_date',
        'position',
    ];

    public function county()
    {
        return $this->belongsTo(User::class, 'county_id');
    }
}
