<?php

namespace App;

use App\Traits\CountableTrait;
use App\Traits\LogModelEvent;
use Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Rutorika\Sortable\SortableTrait;

/**
 * App\Video
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $content
 * @property int $active
 * @property string|null $sort 分類
 * @property string|null $sub_sort 子分類
 * @property int $position 排序位置
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $author
 * @property-read \App\Counter|null $counter
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read int $counter_count
 * @property-read string $link_url
 * @property-read string $sort_name
 * @property-read string $thumbnail_url
 * @method static \Illuminate\Database\Eloquent\Builder|Video newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Video query()
 * @method static \Illuminate\Database\Eloquent\Builder|Video sorted()
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereSubSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Video whereUserId($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    use SortableTrait;
    use LogModelEvent;
    use CountableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'sort',
        'sub_sort',
        'active',
        'position',
    ];

    public static array $sorts = [
        1 => [
            'name'      => '宣傳影片',
            'sub_sorts' => [
                '防災士',
                '韌性社區',
                '年度影音',
            ],
        ],
        2 => [
            'name'      => '摺頁／海報',
            'sub_sorts' => [
                '防災士',
                '韌性社區',
            ],
        ],
        3 => [
            'name'      => '手冊／書籤',
            'sub_sorts' => [
                '防災士',
                '韌性社區',
            ],
        ],
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }

    /**
     * 分類名稱
     * @return string
     */
    public function getSortNameAttribute(): ?string
    {
        if (!$this->sort) {
            return '';
        }
        $sort = Arr::get(Video::$sorts, $this->sort);
        if (!$sort) {
            return $this->sort;
        }

        return $sort['name'] . ' > ' . ($this->sub_sort ?: '其他');
    }

    /**
     * 取得 Youtube 影片 ID
     *
     * @param string $url
     * @return string
     */
    private function getYoutubeVideoId(string $url): ?string
    {
        $youtubeUrlPattern = '#^(?:https?://|//)?(?:www\.|m\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|watch\?.+&v=))([\w-]{11})(?![\w-])#';
        preg_match($youtubeUrlPattern, $url, $matches);
        if (empty($matches) || count($matches) < 2) {
            return null;
        }

        return $matches[1];
    }

    /**
     * 超連結網址
     *
     * @return string
     */
    public function getLinkUrlAttribute(): string
    {
        if ($this->files->count()) {
            return url($this->files->first()->file_path);
        }
        $youtubeVideoId = $this->getYoutubeVideoId($this->content);
        if ($youtubeVideoId) {
            return "https://youtu.be/{$youtubeVideoId}";
        }

        return $this->content;
    }

    /**
     * 縮圖網址
     *
     * @return string
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->files->count()) {
            $firstFile = $this->files->first();
            if (str_ends_with($firstFile->name, '.jpg')) {
                return url($firstFile->path);
            }
            if (str_ends_with($firstFile->name, '.doc')) {
                return url(asset('image/file-word-regular.svg'));
            }
            if (str_ends_with($firstFile->name, '.pdf')) {
                return url(asset('image/file-pdf-regular.svg'));
            }

            return url(asset('image/file-regular.svg'));
        }
        $youtubeVideoId = $this->getYoutubeVideoId($this->content);
        if ($youtubeVideoId) {
            return "https://img.youtube.com/vi/{$youtubeVideoId}/0.jpg";
        }

        return '';
    }
}
