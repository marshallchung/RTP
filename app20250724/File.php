<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\File
 *
 * @property int $id
 * @property string $uid
 * @property int $post_id
 * @property string $post_type
 * @property string $name
 * @property string $path
 * @property string $mime_type
 * @property int $file_size
 * @property bool $opendata
 * @property bool $is_recommend 推薦為優良範本
 * @property bool $is_sample 優良範本
 * @property string $memo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read string $file_path
 * @property-read Model|\Eloquent $post
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereIsRecommend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereIsSample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereOpendata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePostType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'uid',
        'name',
        'path',
        'mime_type',
        'file_size',
        'opendata',
        'is_recommend',
        'is_sample',
        'memo',
        'created_at',
    ];

    protected $casts = [
        'opendata'     => 'bool',
        'is_recommend' => 'bool',
        'is_sample'    => 'bool',
    ];

    public function post()
    {
        return $this->morphTo();
    }

    /**
     * @return string
     */
    public function getFilePathAttribute()
    {
        return str_replace('\\', '/', $this->path);
    }
}
