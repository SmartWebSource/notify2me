<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function phones(){
    	return $this->hasMany(ContactPhone::class);
    }

    public function emails(){
    	return $this->hasMany(ContactEmail::class);
    }

    public function attendees(){
    	return $this->hasMany(MettingAttendee::class);
    }
}
