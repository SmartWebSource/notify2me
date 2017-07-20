<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactEmail extends Model
{
    public $table = 'contact_email';

    public function contact(){
    	return $this->belongsTo(Contact::class);
    }
}
