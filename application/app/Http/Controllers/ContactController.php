<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Contact;
use Illuminate\Http\Request;
use App\ContactNumber;
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
            'id', 'name', 'gender', 'address', 'purpose'
        ]);
        
        //DB::raw("(SELECT GROUP_CONCAT(number SEPARATOR ',') FROM contact_numbers WHERE contact_id = ".$request->id.") as phone_numbers")
        $myNumbers = [];
        $cNum = ContactNumber::whereContactId($request->id)->get(['number']);
        if(count($cNum) > 0){
            foreach($cNum as $num){
                $myNumbers[] = $num->number;
            }
        }
        
        $contact->myNumbers = $myNumbers;
        
        $groups = [];
        if($contact->groups){
            foreach($contact->groups as $grp){
                $groups[] = ['id'=>$grp->group->id,'text'=>$grp->group->title];
            }
        }

        $contact->myGroups = json_encode($groups);
        
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
            'address' => 'max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {
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
            $contact->address = trim($request->address);
            $contact->purpose = trim($request->purpose);

            if ($contact->save()) {
                
                ContactGroup::whereContactId($contact->id)->delete();
                foreach($request->group as $grp){
                    $cg = new ContactGroup();
                    $cg->contact_id = $contact->id;
                    $cg->group_id = $grp;
                    $cg->save();
                }

                ContactNumber::whereContactId($contact->id)->delete();
                $numbers = explode(';', $request->phone_numbers);
                foreach ($numbers as $number) {
                    if(!empty($number)){
                        $cn = new ContactNumber();
                        $cn->contact_id = $contact->id;
                        $cn->number = $number;
                        $cn->save();
                    }
                }

                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'Contact has been successfully saved.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'Contact has not successfully saved.']);
            }
        }
    }
    
    public function getContactNumbersViaContactId(Request $request){
        //dd($request->all());
        return response()->json(['1','2','3']);
    }

}