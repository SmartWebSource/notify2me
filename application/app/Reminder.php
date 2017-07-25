<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon, Mail;
//se Helpers\Classes\EmailSender;

class Reminder extends Model
{
    public function meeting(){
    	return $this->belongsTo(Meeting::class);
    }

    public function getTriggerAtAttribute($value){
        return Carbon::parse($value)->format('Y-m-d h:i A');
    }

    public function remindViaEmail(){

    	$status = 0;
    	$exceptionMessage = "";

    	$meeting = $this->meeting;

    	$meetingTitle = $meeting->title;
    	$meetingDetail = $meeting->details;

    	$attendees = $meeting->attendees;

    	$attendeeArray = [];
		$i = 0;
    	foreach ($attendees as $attendee) {
    		$contact = $attendee->contact;
    		$contactName = $contact->name;
    		$contactEmails = $contact->emails;

    		$attendeeArray[$i]['name'] = $contactName;
    		
    		foreach ($contactEmails as $contactEmail) {
    			$attendeeArray[$i]['emails'][] = $contactEmail->email;
    		}
    		$i++;
    	}

    	if(count($attendeeArray) > 0){

    		try{
    			$data = [
	    			'meetingTitle' => $meetingTitle,
	                'meetingDetail' => $meetingDetail
	    		];

	    		$status = Mail::send('emails.email-notifier', $data, function ($message) use($attendeeArray) {

	    			$message->subject('Meeting Reminder');
				    $message->to('asbs.reminder@gmail.com', 'ASBS');

				    foreach ($attendeeArray as $attendee) {

				    	foreach ($attendee['emails'] as $email) {
				    		$message->cc($email, $attendee['name']);
				    	}
		    		}
				});
    		}catch(\Exception $ex){
    			$exceptionMessage = $ex->getMessage();
    		}
    	}


    	if($status > 0){
    		$this->email_status = 'success';
    		$this->email_status_message = $status. ' emails has been successfully sent.';
    	}else{
    		$this->email_status = 'failed';
    		$this->email_status_message = $exceptionMessage;
    	}
    	$this->save();

    	return $status;
    }

    public function remindViaSMS(){
    	dd('sms',$this);
    }
}
