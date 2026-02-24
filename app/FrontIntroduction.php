<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FrontIntroduction
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $title
 * @property string $for
 * @property string $content
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontIntroduction whereUserId($value)
 * @mixin \Eloquent
 */
class FrontIntroduction extends Model
{
    use LogModelEvent;

    protected $table = 'front_introductions';

    protected $fillable = [
        'user_id',
        'for',
        'title',
        'content',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
