<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MCC extends Model {
    protected $table = 'tbl_maintenance_centre';

    protected $fillable = [
        'check_name', 'work_id'
    ];   
    public $timestamps = false; 
}