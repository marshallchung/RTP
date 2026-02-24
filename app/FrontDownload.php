<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FrontDownload
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrontDownload whereUserId($value)
 * @mixin \Eloquent
 */
class FrontDownload extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'category',
        'title',
        'content',
        'active',
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
