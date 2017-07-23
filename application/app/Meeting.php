<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    public $table = 'meeting';

    public function attendees(){
    	return $this->hasMany(MettingAttendee::class);
    }
}
