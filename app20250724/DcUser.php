<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DcUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $dc_unit_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $active
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \App\DcUnit|null $dcUnit
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereDcUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcUser whereUsername($value)
 * @mixin \Eloquent
 */
class DcUser extends Model implements AuthenticatableContract
{
    use LogModelEvent;
    use Authenticatable;

    protected $fillable = [
        'username',
        'password',
        'dc_unit_id',
        'change_default',
        'next_change',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dcUnit()
    {
        return $this->belongsTo(DcUnit::class);
    }
}
