<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpWaiver
 *
 * @property int $id
 * @property int $dp_score_id
 * @property string $name
 * @property int|null $review_result 審查結果
 * @property string|null $review_comment 審查意見
 * @property string|null $review_at 審查時間
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\DpScore|null $dpScore
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read string $review_result_text
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereDpScoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereReviewAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereReviewComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereReviewResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpWaiver whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DpWaiver extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'name',
        'user_id',
        'apply_unit',
        'review_result',
        'review_comment',
        'review_at',
    ];

    protected $appends = [
        'review_result_text',
    ];

    public function dpScore()
    {
        return $this->belongsTo(DpScore::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }

    /**
     * @return string
     */
    public function getReviewResultTextAttribute()
    {
        if ($this->review_result === null) {
            return '未審查';
        }

        return $this->review_result ? '通過' : '不通過';
    }
}
