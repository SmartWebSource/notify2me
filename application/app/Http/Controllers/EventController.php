<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class EventController extends Controller
{
    public function index(Request $request) {

        $events = [];
        return view('events.list', compact('events'));
    }

    public function create(Request $request) {
        return view('users.create');
    }

    public function edit(Request $request) {
        $user = User::whereId($request->id)->first();
        return response()->json($user);
    }

    public function view($id) {
        return view('users.view');
    }

    public function delete(Request $request) {
        
    }

    public function save(Request $request) {
        $rules = [
            'name' => 'required|max:255',
        ];

        if(isSuperAdmin()){
            $rules['company'] = 'required|max:255';
        }

        if (empty($request->id) || $request->id == 0) {
            $rules['email'] = 'required|email|unique:users';
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {
            if (empty($request->id) || $request->id == 0) {
                //let's add
                $user = new User();

                $user->role = isSuperAdmin() ? 'admin' : 'agent';

                $password = str_random(6);

                $user->email = trim($request->email);
                $user->password = bcrypt($password);
                $user->remember_token = str_random(10);
                $user->created_by = $request->user()->id;
                $user->created_at = Carbon::now();
            } else {
                //let's edit
                $user = User::find($request->id);
                $user->updated_by = $request->user()->id;
                $user->updated_at = Carbon::now();
            }

            $user->company_id = isSuperAdmin() ? $request->company : $request->user()->company_id;
            $user->name = trim($request->name);
            $user->active = $request->has('active') ? true : false;

            if ($user->save()) {

                if (empty($request->id) || $request->id == 0) {
                    //this is a new user registration, let's send access over email
                    $data = [
                        'blade' => 'new-account',
                        'toUser' => $user->email,
                        'toUserName' => $user->name,
                        'subject' => 'New Account at ' . config('constants.default.app_name'),
                        'body' => [
                            'company' => $user->company->name,
                            'name' => $user->name,
                            'username' => $user->email,
                            'password' => $password
                        ]
                    ];
                    \Helpers\Classes\EmailSender::send($data);
                }

                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'User has been successfully saved.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'User has not successfully saved.']);
            }
        }
    }
}
