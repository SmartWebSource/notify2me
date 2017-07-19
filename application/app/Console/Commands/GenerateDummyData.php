<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Company;
use App\Department;
use App\UserPhone;
use App\UserEmail;
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
            $user->username = 'mizanur';
            $user->password = bcrypt('password');
            $user->active = true;
            $user->user_type = 'super-admin';
            $user->last_login = Carbon::now();
            $user->save();

            $userPhone = new UserPhone();
            $userPhone->number = '01713123956';
            $userPhone->save();

            $userEmail = new UserEmail();
            $userEmail->email = 'mizan3008@gmail.com';
            $userEmail->save();

            //create dummy company
            $company = new Company();
            $company->name = 'Lorem Ipsum';
            $company->save();


            //create dummy company
            $department = new Department();
            $department->company_id = $company->id;
            $department->title = 'IT';
            $department->created_by = $user->id;
            $department->save();

            //updating compay id in user table
            $user->company_id = $company->id;
            $user->department_id = $department->id;
            $user->save();

            $this->info('Username: '.$user->username.' | Password: mizanur');
        }catch(\Exception $ex){
            $this->info($ex->getMessage());
        }
    }
}
