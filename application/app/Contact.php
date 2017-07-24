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

    public static function getDropDownList(){
    	$query = Contact::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId(Auth::user()->company_id);
        }
        $contacts = $query->lists('name', 'id');
        return $contacts;
    }
}
