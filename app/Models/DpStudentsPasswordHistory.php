<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DpStudentsPasswordHistory extends Model
{
    use HasFactory;

    protected $table = 'dp_students_password_history';

    protected $fillable = [
        'dp_students_id',
        'password',
    ];
}
