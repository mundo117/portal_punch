<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BioTimeUsersModel extends Model
{
    protected $table = 'userinfo';
    protected $primaryKey = 'userid';
    protected $fillable = [
        'name','lastname', 'nickname','status','badgenumber','app_status','ATT','defaultdeptid'
    ];
    public $timestamps = false;
}
