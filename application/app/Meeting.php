<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon, Auth;

class Meeting extends Model
{
    protected $table = 'meeting';

    public function attendees(){
    	return $this->hasMany(MeetingAttendee::class);
    }

    public function reminders(){
        return $this->hasMany(Reminder::class);
    }

    public function getNextMeetingDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }

    public static function getDropDownList(){
    	$query = Meeting::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId(Auth::user()->company_id);
        }
        $meeting = $query->lists('title', 'id');
        array_add($meeting, '', '-- Select One --');
        return $meeting;
    }
}
