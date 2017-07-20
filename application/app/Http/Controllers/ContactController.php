<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contact;
use Illuminate\Http\Request;
use App\ContactPhone;
use App\ContactEmail;
use Validator,
    Carbon, DB;
use App\ContactGroup;

class ContactController extends Controller {

    public function index(Request $request) {

        $query = Contact::query();
        if ($request->user()->role != 'super-admin') {
            $query->whereCompanyId($request->user()->company_id);
        }
        $contacts = $query->orderBy('id', 'desc')->paginate(20);
        $contacts->paginationSummery = getPaginationSummery($contacts->total(), $contacts->perPage(), $contacts->currentPage());
        return view('contacts.list', compact('contacts'));
    }

    public function create(Request $request) {
        return view('contacts.create');
    }

    public function edit(Request $request) {
        $contact = Contact::whereId($request->id)->first([
            'id', 'name', 'gender', 'address'
        ]);

        $contactPhones = [];
        $cNum = $contact->phones;
        if(count($cNum) > 0){
            foreach($cNum as $num){
                $contactPhones[] = $num->number;
            }
        }
        
        $contact->contactPhones = implode(';', $contactPhones);

        $contactEmails = [];
        $cEmail = $contact->emails;
        if(count($cEmail) > 0){
            foreach($cEmail as $email){
                $contactEmails[] = $email->email;
            }
        }
        
        $contact->contactEmails = implode(';', $contactEmails);
        
        return response()->json($contact);
    }

    public function view($id) {
        return view('contacts.view');
    }

    public function delete(Request $request) {
        
    }

    public function save(Request $request) {
        $rules = [
            'name' => 'required|max:255',
            'phone_numbers' => 'required',
            'email_addresses' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {

            $validatorError = [];

            $isContactPhoneExists = $this->isContactPhoneExists(['reference'=>$request->id,'numbers'=>$request->phone_numbers]);

            if(!empty($isContactPhoneExists)){
                $validatorError['phone_numbers'] = $isContactPhoneExists.' already exists.';
            }

            $isContactEmailExists = $this->isContactEmailExists(['reference'=>$request->id,'emails'=>$request->email_addresses]);

            if(!empty($isContactEmailExists)){
                $validatorError['email_addresses'] = $isContactEmailExists.' already exists.';
            }

            if(count($validatorError) > 0){
                return response()->json(['status' => 400, 'error' => $validatorError]);
            }

            if (empty($request->id) || $request->id == 0) {
                //let's add
                $contact = new Contact();
                $contact->company_id = $request->user()->company_id;
                $contact->created_by = $request->user()->id;
                $contact->created_at = Carbon::now();
            } else {
                //let's edit
                $contact = Contact::find($request->id);
                $contact->updated_by = $request->user()->id;
                $contact->updated_at = Carbon::now();
            }
            $contact->name = trim($request->name);
            $contact->gender = $request->gender;

            if ($contact->save()) {

                $contactPhones = ContactPhone::whereContactId($contact->id)->get();
                if($contactPhones){
                    ContactPhone::whereContactId($contact->id)->delete();
                }

                foreach (explode(';', $request->phone_numbers) as $number) {
                    if(!empty($number)){
                        $cp = new ContactPhone();
                        $cp->contact_id = $contact->id;
                        $cp->number = $number;
                        $cp->save();
                    }
                }


                $contactEmails = ContactEmail::whereContactId($contact->id)->get();
                if($contactEmails){
                    ContactEmail::whereContactId($contact->id)->delete();
                }

                foreach (explode(';', $request->email_addresses) as $email) {
                    if(!empty($email)){
                        $cp = new ContactEmail();
                        $cp->contact_id = $contact->id;
                        $cp->email = $email;
                        $cp->save();
                    }
                }

                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'Contact has been successfully saved.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'Contact has not successfully saved.']);
            }
        }
    }

    private function isContactPhoneExists($data){
        $query = ContactPhone::whereIn('number',explode(';', $data['numbers']));
        if(!empty($data['reference'])){
            $query->where('contact_id','!=',$data['reference']);
        }

        $result = $query->get(['number']);

        if(count($result) <= 0){
            return '';
        }

        $numArr = [];
        foreach ($result as $row) {
            $numArr[] = $row->number;
        }

        return implode(',', $numArr);
    }

    private function isContactEmailExists($data){
        $query = ContactEmail::whereIn('email',explode(';', $data['emails']));
        if(!empty($data['reference'])){
            $query->where('contact_id','!=',$data['reference']);
        }

        $result = $query->get(['email']);

        if(count($result) <= 0){
            return '';
        }

        $emailArr = [];
        foreach ($result as $row) {
            $emailArr[] = $row->email;
        }

        return implode(',', $emailArr);
    }
    
    public function getContactNumbersViaContactId(Request $request){
        //dd($request->all());
        return response()->json(['1','2','3']);
    }

}