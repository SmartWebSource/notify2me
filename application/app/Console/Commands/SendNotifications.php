<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Reminder;
use Carbon;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendNotifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send notifications (sms/email) based on reminders table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Reminder::query();

        /*$query->where(function ($q) {
            $q->where('email_payload', '!=', '')->orWhere('sms_payload', '!=', '');
        });

        $query->where(function($q){
            $q->where('email_status','!=','success')->orWhere('sms_status','!=','success');
        });*/

        $query->where('email_payload', '!=', '')->where('email_status','!=','success');

        //error_log($query->toSql());

        $reminders = $query->get();

        if(count($reminders) > 0){
            foreach ($reminders as $reminder) {
    
                $currentTime = Carbon::now($reminder->timezone);
                $triggerTime = Carbon::parse($reminder->trigger_at);
    
                if($reminder->email_status == 'failed'){
                    $reminder->remindViaEmail();
                }else{
    
                    if($currentTime->gte($triggerTime)){
                        $reminder->remindViaEmail();
                    }
                }
    
                /*if($reminder->sms_status == 'failed'){
                    $reminder->remindViaSMS();
                }else{
                    if($currentTime->gte($triggerTime)){
                        $reminder->remindViaSMS();
                    }   
                }*/
            }

            $this->info('200');
        }else{
            $this->info('nothing found');
        }
    }
}
