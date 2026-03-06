<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpResource
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpResource whereUserId($value)
 * @mixin \Eloquent
 */
class DpResource extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'name',
        'user_id',
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
