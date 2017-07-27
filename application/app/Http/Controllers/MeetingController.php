<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Meeting;
use Validator,Carbon;
use App\MeetingAttendee;

class MeetingController extends Controller
{
    public function index(Request $request) {

        $query = Meeting::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId($request->user()->company_id);
        }
        $meetings = $query->orderBy('id', 'desc')->paginate(25);
        $meetings->paginationSummery = getPaginationSummery($meetings->total(), $meetings->perPage(), $meetings->currentPage());
        return view('meeting.list', compact('meetings'));
    }

    public function create(Request $request) {
        return view('meeting.create');
    }

    public function edit(Request $request) {
        $meeting = Meeting::whereId($request->id)->first();

        $myAttendees = [];
        foreach ($meeting->attendees as $attendee) {
            $myAttendees[] = $attendee->contact->id;
        }
        $meeting->myAttendees = json_encode($myAttendees);

        return response()->json($meeting);
    }

    public function view($id) {
        return view('meeting.view');
    }

    public function delete(Request $request) {
        
    }

    public function save(Request $request) {
        $rules = [
            'title' => 'required',
            'next_meeting_date' => 'required',
            'concern_person_name' => 'required',
            'meeting_details' => 'required',
            'attendee' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {
            if (empty($request->id) || $request->id == 0) {
                //let's add
                $meeting = new Meeting();
                $meeting->created_by = $request->user()->id;
                $meeting->created_at = Carbon::now();
                
            } else {
                //let's edit
                $meeting = Meeting::find($request->id);
                $meeting->updated_by = $request->user()->id;
                $meeting->updated_at = Carbon::now();
            }

            $meeting->title = trim($request->title);
            $meeting->details = $request->meeting_details;
            $meeting->next_meeting_date = Carbon::parse($request->next_meeting_date)->format('Y-m-d H:i:s');
            $meeting->concern_person_name = trim($request->concern_person_name);
            $meeting->concern_person_phone = trim($request->concern_person_phone);
            $meeting->concern_person_designation = trim($request->concern_person_designation);

            if ($meeting->save()) {

                $meetingAttendee = MeetingAttendee::whereMeetingId($meeting->id)->get();
                if($meetingAttendee){
                    MeetingAttendee::whereMeetingId($meeting->id)->delete();
                }

                foreach ($request->attendee as $attendee) {
                    if(!empty($attendee)){
                        $ma = new MeetingAttendee();
                        $ma->meeting_id = $meeting->id;
                        $ma->contact_id = $attendee;
                        $ma->save();
                    }
                }

                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'Meeting has been successfully saved.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'Meeting has not successfully saved.']);
            }
        }
    }

    public function meetingJson(Request $request){
        $query = Meeting::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId($request->user()->company_id);
        }
        $meetings = $query->get(['id','title', 'details','next_meeting_date']);

        $calendarData = [];

        $i = 0;
        foreach ($meetings as $metting) {
            $calendarData[$i]['id'] = $metting->id;
            $calendarData[$i]['title'] = $metting->title;
            $calendarData[$i]['details'] = $metting->details;
            $calendarData[$i]['start'] = $metting->next_meeting_date;
            //$calendarData[$i]['end'] = $metting->next_meeting_date;
            $calendarData[$i]['allDay'] = true;
            $i++;
        }

        return response()->json($calendarData);
    }
}
