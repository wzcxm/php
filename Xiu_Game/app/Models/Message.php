<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $table = 'xx_sys_message';
    protected $primaryKey = 'msgid';
    public $timestamps = false;
}
