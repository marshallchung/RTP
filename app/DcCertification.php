<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DcCertification
 *
 * @property int $id
 * @property int $user_id
 * @property int $dc_unit_id
 * @property string|null $term
 * @property int|null $review_result 審查結果
 * @property string|null $review_comment 審查意見
 * @property string|null $review_at 審查時間
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\DcUnit|null $dcUnit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read string $review_result_text
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification query()
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereDcUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereReviewAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereReviewComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereReviewResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcCertification whereUserId($value)
 * @mixin \Eloquent
 */
class DcCertification extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'dc_unit_id',
        'term',
        'review_result',
        'review_comment',
        'review_at',
    ];

    public function dcUnit()
    {
        return $this->belongsTo(DcUnit::class);
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
