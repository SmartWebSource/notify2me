<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Reminder extends Model
{
    public function meeting(){
    	return $this->belongsTo(Meeting::class);
    }

    public function getTriggerAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }
}
