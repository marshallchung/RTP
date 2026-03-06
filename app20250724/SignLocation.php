<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\SignLocation
 *
 * @property int $id
 * @property int $user_id
 * @property float|null $latitude 緯度
 * @property float|null $longitude 經度
 * @property string|null $description 簡介
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read string $info
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SignLocation whereUserId($value)
 * @mixin \Eloquent
 */
class SignLocation extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }

    /**
     * @return string
     */
    public function getInfoAttribute()
    {
        //簡介
        $info = link_to_route('admin.sign-location.show', $this->description ?: '（無簡介）', $this) . '<br/>';
        //檔案連結
        if (count($this->files)) {
            $info .= '<ul>';
            foreach ($this->files as $file) {
                //                $info .= "<li><a href='/{$file->path}' target='_blank'>{$file->name}</a></li>";
                $url = 'https://pdmcb.nfa.gov.tw/' . $file->path;
                $info .= "<li><a href='{$url}' target='_blank'>{$file->name}</a></li>";
            }
            $info .= '</ul>';
        }
        $info .= '<br/>';
        //上傳單位
        $info .= '上傳單位：' . $this->user->name . '<br/>';
        //上傳時間
        $info .= '上傳時間：' . $this->created_at . '<br/>';

        return $info;
    }
}
