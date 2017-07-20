<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    public function users(){
    	return $this->hasMeny(User::class);
    }

    public static function getDropDownList(){
    	$company = Company::lists('name','id');
    	array_add($company, '', '-- Select One --');
    	return $company;
    }
}
