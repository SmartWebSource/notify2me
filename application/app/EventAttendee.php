<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventAttendee extends Model
{
    public $timestamps = false;

    public function event(){
    	return $this->belongsTo(Event::class);
    }

    public function contact(){
    	return $this->belongsTo(Contact::class);
    }
}
