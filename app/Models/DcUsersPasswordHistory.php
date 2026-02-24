<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DcUsersPasswordHistory extends Model
{
    use HasFactory;

    protected $table = 'dc_users_password_history';

    protected $fillable = [
        'dc_users_id',
        'password',
    ];
}
