<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingAttendee extends Model
{
    public $timestamps = false;

    public function meeting(){
    	return $this->belongsTo(Meeting::class);
    }

    public function contact(){
    	return $this->belongsTo(Contact::class);
    }
}
