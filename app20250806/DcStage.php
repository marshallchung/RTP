<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DcStage
 *
 * @property int $id
 * @property int $user_id
 * @property int $dc_unit_id
 * @property int $stage
 * @property int $term
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \App\DcUnit|null $dcUnit
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage query()
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereDcUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereStage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DcStage whereUserId($value)
 * @mixin \Eloquent
 */
class DcStage extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'dc_unit_id',
        'stage',
        'term',
        'active',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dcUnit()
    {
        return $this->belongsTo(DcUnit::class);
    }
}
