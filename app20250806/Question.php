<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Question
 *
 * @property int $id
 * @property int $questionnaire_id
 * @property int $seq
 * @property string $code
 * @property int $indent
 * @property string $type
 * @property string|null $score_type 指標類型
 * @property string|null $content
 * @property string|null $options
 * @property int $upload
 * @property float|null $gain
 * @property float|null $extra_gain
 * @property float|null $score_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $comment
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\Question|null $parent
 * @property-read \App\Questionnaire|null $questionnaire
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereExtraGain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereGain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereIndent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereQuestionnaireId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereScoreLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereScoreType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereSeq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpload($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    use LogModelEvent;

    //use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'seq',
        'questionnaire_id',
        'code',
        'type',
        'score_type',
        'content',
        'options',
        'upload',
        'gain',
        'extra_gain',
        'score_limit',
        'indent',
        'comment',
    ];

    public function questionnaire()
    {
        return $this->belongsTo('App\Questionnaire');
    }

    public function files()
    {
        return $this->morphMany('App\File', 'post');
    }

    /**
     * @return \App\Question|null
     */
    public function getParentAttribute()
    {
        $parentCode = $this->code;
        while (Str::contains($parentCode, ['.'])) {
            $parentCode = substr($parentCode, 0, strrpos($parentCode, '.'));
            $cacheKey = 'question_code_' . $parentCode;
            $parent = \Cache::remember($cacheKey, 60, function () use ($parentCode) {
                //TODO: 可能會造成大量query，暫時先用短暫緩存處理，需改善處理方式
                //方案一：無法搭配 eager loading，會有大量 query
                //                return static::whereQuestionnaireId($this->questionnaire_id)->whereCode($parentCode)->first();
                //方案二：可搭配 eager loading，但搭配實最造成記憶體爆量
                return $this->questionnaire->questions->where('code', $parentCode)->first();
            });
            if ($parent) {
                return $parent;
            }
        }

        return null;
    }
}
