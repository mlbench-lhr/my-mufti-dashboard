<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{Event, Question, User, UserQuery, Interest, MuftiAppointment, EventQuestion, EventScholar};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventsAndApptController extends Controller
{
    public  function all_appointments()
    {
        $appts = MuftiAppointment::get();
        return view('frontend.AllAppointments', compact('appts'));
    }
    public function get_all_appointments(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = MuftiAppointment::count();
        $query = MuftiAppointment::with('user_detail', 'mufti_detail');

        if ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'desc');

        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function appointment_detail(Request $request)
    {
        $detail = MuftiAppointment::with('user_detail', 'mufti_detail.interests')->where('id', $request->id)->first();
        return view('frontend.AppointmentDetail', compact('detail'));
    }

    // Events

    public  function all_events()
    {
        $events = Event::where('event_status', 1)->get();
        return view('frontend.AllEvents', compact('events'));
    }
    public function get_all_events(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = Event::where('event_status', '1')->count();
        $query = Event::with('scholars', 'hosted_by', 'event_questions');

        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'desc');
        $query->where('event_status', '1');
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->event_date = Carbon::parse($row->date)->format('M d, Y');
            $row->event_time = Carbon::parse($row->date)->format('H:i A');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public  function all_requested_events()
    {
        $events = Event::get();
        return view('frontend.RequestedEvents', compact('events'));
    }

    public function event_detail(Request $request)
    {   
        $event_id = $request->id;
        $event = Event::with('hosted_by', 'scholars', 'event_questions')->where('id', $request->id)->first();
        $event_scholar = EventScholar::where('event_id', $request->id)->limit(2)->get();
        $all_event_scholar = EventScholar::where('event_id', $request->id)->get();
        return view('frontend.EventDetail', compact('event', 'event_id', 'event_scholar', 'all_event_scholar'));
    }

    public function event_question_detail(Request $request)
    {   
        $detail = EventQuestion::with('user_detail')->where('id', $request->id)->first();
        return view('frontend.EventQuestionDetail', compact('detail'));
    }

    public function get_event_questions(Request $request)
    {
        $searchTerm = $request->input('search');

        $userCount = EventQuestion::with('user_detail')->where('event_id', $request->id)->count();
        $query = EventQuestion::with('user_detail')->where('event_id', $request->id);

       
        $user = $query->paginate(3);
        foreach ($user as $row) {
            $row->posted_at = Carbon::parse($row->created_at)->format('M d, Y');
            
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function get_all_requested_events(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = Event::count();
        $query = Event::with('scholars', 'hosted_by', 'event_questions');

        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'desc');
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->event_date = Carbon::parse($row->date)->format('M d, Y');
            $row->event_time = Carbon::parse($row->date)->format('H:i A');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }


    public function approve_request(Request $request, $id)
    {
        $event = Event::where('id', $request->id)->first();
        $event->event_status = 1;
        $event->save();
        return back();
    }


    public function reject_request(Request $request, $id)
    {
        $event = Event::where('id', $request->id)->first();
        $event->event_status = 0;
        $event->save();
        return back();
    }
}
