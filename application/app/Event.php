<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Event extends Model
{
    public function attendees(){
    	return $this->hasMany(EventAttendee::class);
    }

    public function reminders(){
        return $this->hasMany(Reminder::class);
    }

    public function getStartDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }

    public function getEndDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }

    public static function getDropDownList(){
    	$query = Event::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId(Auth::user()->company_id);
        }
        $events = $query->lists('title', 'id');
        array_add($events, '', '-- Select One --');
        return $events;
    }
}
