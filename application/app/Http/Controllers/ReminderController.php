<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Reminder;
use Validator, Carbon;

class ReminderController extends Controller
{
    public function index(Request $request) {

        $query = Reminder::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId($request->user()->company_id);
        }
        $reminders = $query->orderBy('id', 'desc')->paginate(20);
        $reminders->paginationSummery = getPaginationSummery($reminders->total(), $reminders->perPage(), $reminders->currentPage());
        return view('reminders.list', compact('reminders'));
    }

    public function create(Request $request) {
        return view('reminders.create');
    }

    public function edit(Request $request) {
        $reminder = Reminder::whereId($request->id)->first();
        return response()->json($reminder);
    }

    public function view($id) {
        return view('reminders.view');
    }

    public function delete(Request $request) {
        
    }

    public function save(Request $request) {
        $rules = [
            'meeting' => 'required',
            'timezone' => 'required',
            'remind_at' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {
            if (empty($request->id) || $request->id == 0) {
                //let's add
                $reminder = new Reminder();
                $reminder->created_at = Carbon::now();
            } else {
                //let's edit
                $reminder = Reminder::find($request->id);
                $reminder->updated_at = Carbon::now();
            }

            $reminder->company_id = $request->user()->company_id;
            $reminder->meeting_id = $request->meeting;
            $reminder->timezone = $request->timezone;
            $reminder->trigger_at = Carbon::parse($request->remind_at)->format('Y-m-d H:i:s');
            $reminder->email_payload = $request->has('remind_via_email') ? 'true' : '';
            $reminder->sms_payload = $request->has('remind_via_sms') ? 'true' : '';

            if ($reminder->save()) {

                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'Reminder has been successfully set.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'Reminder has not successfully set.']);
            }
        }
    }
}
