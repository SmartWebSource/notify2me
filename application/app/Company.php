<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public function users(){
    	return $this->hasMany(User::class);
    }

    public function contacts(){
    	return $this->hasMany(Contact::class);
    }

    public function events(){
    	return $this->hasMany(Event::class);
    }

    public static function getDropDownList(){
    	$company = Company::lists('name','id');
    	array_add($company, '', '-- Select One --');
    	return $company;
    }
}
