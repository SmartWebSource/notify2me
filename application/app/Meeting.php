<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Meeting extends Model
{
    public $table = 'meeting';

    public function attendees(){
    	return $this->hasMany(MettingAttendee::class);
    }

    public function getNextMeetingDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }
}
