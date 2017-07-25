<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    protected $table = 'contact_phone';

    public function contact(){
    	return $this->belongsTo(Contact::class);
    }
}
