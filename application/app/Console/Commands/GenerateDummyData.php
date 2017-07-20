<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Company;
use App\Contact;
use App\ContactPhone;
use App\ContactEmail;
use Carbon;

class GenerateDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateDummyData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will setup dummy data';

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
        try{
            //create dummy user
            $user = new User();
            $user->name = 'Mizanur Rahman';
            $user->email = 'mizanur.rahman@smartwebsource.com';
            $user->password = bcrypt('mizanur.rahman@smartwebsource.com');
            $user->active = true;
            $user->role = 'super-admin';
            $user->last_login = Carbon::now();
            $user->save();

            //create dummy company
            $company = new Company();
            $company->name = 'Lorem Ipsum';
            $company->created_by = $user->id;
            $company->save();

            //create dummy contact
            $contact = new Contact();
            $contact->company_id = $company->id;
            $contact->name = 'Roni';
            $contact->gender = 'male';
            $contact->created_by = $user->id;
            $contact->save();

            $contactPhone = new ContactPhone();
            $contactPhone->contact_id = $contact->id;
            $contactPhone->number = '01713123956';
            $contactPhone->save();

            $contactEmail = new ContactEmail();
            $contactEmail->contact_id = $contact->id;
            $contactEmail->email = 'mizanur.rahman@smartwebsource.com';
            $contactEmail->save();

            //updating compay id in user table
            $user->company_id = $company->id;
            $user->created_by = $user->id;
            $user->save();

            $this->info('Username: '.$user->email);
        }catch(\Exception $ex){
            $this->info($ex->getMessage());
        }
    }
}
