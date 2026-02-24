<?php

namespace App;

use App\Traits\LogModelEvent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\DpTeacher
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $tid 身分證
 * @property string $name
 * @property string|null $belongsTo
 * @property string|null $title 職別
 * @property string|null $content
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $mobile
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $location 區域位置
 * @property string|null $address 現居地址
 * @property string|null $addressId 地址識別碼
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\DpTeacherSubject[] $dpTeacherSubjects
 * @property-read int|null $dp_teacher_subjects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereBelongsTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereTid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DpTeacher whereUserId($value)
 * @mixin \Eloquent
 */
class DpTeacher extends Model
{
    use LogModelEvent;

    protected $fillable = [
        'user_id',
        'belongsTo',
        'title',
        'name',
        'tid',
        'content',
        'email',
        'phone',
        'mobile',
        'location',
        'address',
        'addressId',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dpTeacherSubjects()
    {
        return $this->hasMany(DpTeacherSubject::class)
            ->join('dp_subjects', 'dp_subjects.id', '=', 'dp_teacher_subjects.dp_subject_id')
            ->orderBy('dp_subjects.position');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'post');
    }
}
