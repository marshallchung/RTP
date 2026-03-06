<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Counter
 *
 * @property string $name 名稱
 * @property int $count 計算數量
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $countable_type
 * @property string|null $countable_id
 * @property-read Model|\Eloquent $countable
 * @method static \Illuminate\Database\Eloquent\Builder|Counter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Counter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Counter query()
 * @method static \Illuminate\Database\Eloquent\Builder|Counter whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counter whereCountableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counter whereCountableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Counter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Counter extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    protected $fillable = [
        'name',
        'count',
    ];

    public function countable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
