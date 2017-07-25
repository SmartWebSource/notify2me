<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Country extends Model
{
	protected $table = 'country';
	protected $fillable = array('country_code', 'name', 'mobile_code');
	
	public static function countryNames(){
		$result = DB::table('country')->orderBy('name','asc')->lists('name','country_code');
		if($result){
			return $result;
		}else{
			return null;
		}
	}

	public static function countryMobileCodes(){
		$result = Country::lists('mobile_code','country_code')->all();
		if($result){
			return $result;
		}else{
			return null;
		}
	}
}
