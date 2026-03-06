<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpDocument
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpDocument whereYear($value)
 * @mixin \Eloquent
 */
class DpDocument extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
