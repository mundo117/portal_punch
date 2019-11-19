<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckInOutModel extends Model
{
    //checkinout
    protected $table = 'checkinout';
    protected $primaryKey = 'id';
    protected $fillable = [
        'userid','checktime','upload_time', 'userid',
    ];
}
