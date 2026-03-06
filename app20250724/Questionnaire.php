<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Questionnaire
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string|null $date_from
 * @property string|null $expire_soon_date
 * @property string|null $date_to
 * @property int|null $original_total_score 原始總分
 * @property float $basic_weight 基本指標加權
 * @property float $advanced_weight 進階指標加權
 * @property string|null $exception_ids
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Question[] $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire query()
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereAdvancedWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereBasicWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereExceptionIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereOriginalTotalScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questionnaire whereUserId($value)
 * @mixin \Eloquent
 */
class Questionnaire extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'title',
        'type',
        'date_from',
        'expire_soon_date',
        'date_to',
        'original_total_score',
        'basic_weight',
        'advanced_weight',
        'exception_ids',
        'user_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'answers', 'comments')->withTimestamps();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
