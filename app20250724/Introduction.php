<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Rutorika\Sortable\SortableTrait;

/**
 * App\Introduction
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property int $active
 * @property int $position 排序位置
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $introduction_type_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\IntroductionType|null $introductionType
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereIntroductionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Introduction whereUserId($value)
 * @mixin \Eloquent
 */
class Introduction extends Model
{
    use SortableTrait;
    use LogModelEvent;

    protected $nullable = [
        'introduction_type_id',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'active',
        'position',
        'introduction_type_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }

    public function introductionType()
    {
        return $this->belongsTo(IntroductionType::class);
    }
}
