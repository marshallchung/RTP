<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ReportPublicDate
 *
 * @property int $id
 * @property string $year
 * @property \Illuminate\Support\Carbon $public_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate wherePublicDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportPublicDate whereYear($value)
 * @mixin \Eloquent
 */
class ReportPublicDate extends Model
{
    use LogModelEvent;

    protected $dates = ['public_date', 'expire_soon_date', 'expire_date'];

    protected $fillable = ['year', 'public_date', 'expire_soon_date', 'expire_date', 'date_type'];
}
