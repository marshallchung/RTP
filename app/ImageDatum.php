<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\ImageDatum
 *
 * @property int $id
 * @property int $user_id
 * @property int $image_datum_type_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\ImageDatumType $imageDatumType
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum whereImageDatumTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImageDatum whereUserId($value)
 * @mixin \Eloquent
 */
class ImageDatum extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'image_datum_type_id',
    ];

    static public function getDataList($county_id, $district = null, $type = null, $search = null)
    {
        $imageQuery = User::select(DB::raw('users.name,users.type,image_datum_types.name as type_name,GROUP_CONCAT(DISTINCT image_data.id) AS image_list'))
            ->join('image_data', function ($join) use ($type) {
                $join->on('image_data.user_id', '=', 'users.id');
                if ($type) {
                    $join->where('image_datum_type_id', '=', $type);
                }
            })
            ->join('image_datum_types', 'image_datum_types.id', '=', 'image_data.image_datum_type_id')
            ->join('files', function ($join) use ($search) {
                $join->on('files.post_id', '=', 'image_data.id')
                    ->where('files.post_type', '=', self::class);
                if ($search) {
                    $join->where('files.name', 'LIKE', "%$search%");
                }
            })
            ->where(function ($query) use ($county_id) {
                $query->where('users.id', '=', $county_id)
                    ->orWhere('users.county_id', '=', $county_id);
            });
        if ($district) {
            $imageQuery->where('users.id', '=', $district);
        }
        $imageQuery->groupBy(['image_data.created_at', 'image_data.user_id'])
            ->orderBy('image_data.user_id', 'desc')
            ->orderBy('image_data.created_at', 'asc');
        return $imageQuery;
    }

    public function imageDatumType()
    {
        return $this->belongsTo(ImageDatumType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
