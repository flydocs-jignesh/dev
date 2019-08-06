<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model {
    protected $table = 'aircraft_tail';

    protected $fillable = [
        'MSNNO', 'TAIL', 'TYPEID'
    ];
}