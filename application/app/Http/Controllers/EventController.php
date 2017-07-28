<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;
use Validator,Carbon;
use App\EventAttendee;

class EventController extends Controller
{
    public function index(Request $request) {

        $query = Event::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId($request->user()->company_id);
        }
        $events = $query->orderBy('id', 'desc')->paginate(25);
        $events->paginationSummery = getPaginationSummery($events->total(), $events->perPage(), $events->currentPage());
        return view('events.list', compact('events'));
    }

    public function create(Request $request) {
        return view('events.create');
    }

    public function edit(Request $request) {
        $event = Event::whereId($request->id)->first();

        $myAttendees = [];
        foreach ($event->attendees as $attendee) {
            $myAttendees[] = $attendee->contact->id;
        }
        $event->myAttendees = json_encode($myAttendees);

        return response()->json($event);
    }

    public function view($id) {
        return view('events.view');
    }

    public function delete(Request $request) {
        
    }

    public function save(Request $request) {
        $rules = [
            'title' => 'required',
            'start_date' => 'required',
            'attendee' => 'required'
        ];

        if($request->type == 'official'){
        	$rules['concern_person_name'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {
            if (empty($request->id) || $request->id == 0) {
                //let's add
                $event = new Event();
                $event->company_id = $request->user()->company_id;
                $event->created_by = $request->user()->id;
                $event->created_at = Carbon::now();                
            } else {
                //let's edit
                $event = Event::find($request->id);
                $event->updated_by = $request->user()->id;
                $event->updated_at = Carbon::now();
            }

            $event->type = $request->type;
            $event->title = trim($request->title);
            $event->start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $event->end_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $event->location = '';
            $event->description = $request->description;

            if($request->type == 'official'){            
	            $event->concern_person_name = trim($request->concern_person_name);
	            $event->concern_person_phone = trim($request->concern_person_phone);
	            $event->concern_person_designation = trim($request->concern_person_designation);
			}
			$event->priority = $request->priority;

            if($event->priority == 'normal'){
                $color = '#4caf50';
            }elseif($event->priority == 'high'){
                $color = '#ff9800';
            }elseif($event->priority == 'highest'){
                $color = '#e51c23';
            }else{
                $color = '#2196f3';
            }

            $event->color = $color;

            if ($event->save()) {

                $eventAttendee = EventAttendee::whereEventId($event->id)->get();
                if($eventAttendee){
                    EventAttendee::whereEventId($event->id)->delete();
                }

                foreach ($request->attendee as $attendee) {
                    if(!empty($attendee)){
                        $ma = new EventAttendee();
                        $ma->event_id = $event->id;
                        $ma->contact_id = $attendee;
                        $ma->save();
                    }
                }

                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'Event has been successfully saved.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'Event has not successfully saved.']);
            }
        }
    }

    public function eventJson(Request $request){
        $query = Event::query();
        if (!isSuperAdmin()) {
            $query->whereCompanyId($request->user()->company_id);
        }
        $events = $query->get(['id','title', 'description','start_date','color']);

        $calendarData = [];

        $i = 0;
        foreach ($events as $event) {
            $calendarData[$i]['id'] = $event->id;
            $calendarData[$i]['title'] = $event->title;
            $calendarData[$i]['details'] = $event->description;
            $calendarData[$i]['start'] = Carbon::parse($event->start_date)->format('Y-m-d');
            //$calendarData[$i]['end'] = Carbon::parse($event->end_date)->format('Y-m-d');
            $calendarData[$i]['color'] = $event->color;
            $calendarData[$i]['allDay'] = true;
            $i++;
        }

        return response()->json($calendarData);
    }
}
