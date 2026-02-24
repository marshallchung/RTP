<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\UserAlias
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $username
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAlias whereUsername($value)
 * @mixin \Eloquent
 */
class UserAlias extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'username',
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
