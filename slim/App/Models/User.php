<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'fd_users';

    protected $fillable = [
        'name', 'email', 'password', 'api_key'
    ];
    protected $guarded = ['id', 'password'];
    protected $hidden = [
        'password',
    ];
}