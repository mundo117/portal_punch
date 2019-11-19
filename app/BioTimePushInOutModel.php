<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BioTimePushInOutModel extends Model
{
    protected $table = 'checkinout';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name','lastname', 'nickname','status','badgenumber','app_status','ATT'
    ];
}
