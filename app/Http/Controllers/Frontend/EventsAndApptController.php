<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventScholar;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\User;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsAndApptController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function all_appointments()
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
            $query->where(function ($query) use ($searchTerm) {
                $query->whereHas('mufti_detail', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                    ->orWhereHas('user_detail', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
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
    public function all_events()
    {
        $events = Event::whereIn('event_status', [0, 1])->get();
        return view('frontend.AllEvents', compact('events'));
    }
    public function get_all_events(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = Event::whereIn('event_status', [0, 1])->count();
        $query = Event::with('scholars', 'hosted_by', 'event_questions');

        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->whereIn('event_status', [0, 1]);
        $query->orderBy('created_at', 'desc');

        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->event_date = Carbon::parse($row->date)->format('M d, Y');
            $row->event_time = Carbon::parse($row->date)->format('H:i A');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function all_requested_events()
    {
        $events = Event::where('event_status', 2)->get();
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
        $userCount = Event::where('event_status', 2)->count();
        $query = Event::with('scholars', 'hosted_by', 'event_questions');

        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'desc');
        $query->where('event_status', 2);
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

        $eventDate = Carbon::parse($event->date);
        $currentDateTime = Carbon::now();
        // $timezone = 'Asia/Karachi';
        // $currentDateTime = Carbon::now($timezone);
        // $eventDateInCurrentTimezone = $eventDate->copy()->setTimezone($timezone);
        if ($eventDate->greaterThanOrEqualTo($currentDateTime)) {
            $event->event_status = 1;
            $event->save();
            $event_date = Carbon::parse($event->date)->format('M d, Y');
            $user_id = $event->user_id;
            $user_data = User::find($user_id);
            $device_id = $user_data->device_id;
            $title = "Event Request Update";

            $notiBody = 'Your request for ' . $event->event_title . ' on ' . $event_date . ' has been approved.';
            $body = 'Your request for ' . $event->event_title . ' on ' . $event_date . ' has been approved.';
            $messageType = "Event Request Update";
            $otherData = "Event Request Update";
            $notificationType = "0";

            if ($device_id != "") {
                $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
            }

            $data = [
                'user_id' => $user_data->id,
                'title' => $title,
                'body' => $body,
            ];
            Notification::create($data);

            $eventScholars = EventScholar::where('event_id', $request->id)
                ->pluck('user_id')
                ->filter(function ($value) {
                    return $value != 0;
                })
                ->toArray();
            array_walk($eventScholars, function ($value) use ($event_date, $user_data, $event) {
                $user = User::find($value);
                $device_id = $user->device_id;
                $title = "You've Been Added to a New Event!";
                $notiBody = $user_data->name . " has invited you to participate as a scholar in their event: " . $event->event_title;
                $body = $user_data->name . " has invited you to participate as a scholar in their event: " . $event->event_title;
                $messageType = "You've Been Added to a New Event!";
                $otherData = "You've Been Added to a New Event!";
                $notificationType = "0";

                if ($device_id != "") {
                    $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
                }

                $data = [
                    'user_id' => $user->id,
                    'title' => $title,
                    'body' => $body,
                ];

                Notification::create($data);
            });

            $data = array(
                'status' => 'success',
                'message' => 'Event accepted successfully.',
            );
        } else {

            $data = array(
                'response' => 'error',
                'message' => 'Event date is passed, you are unable to accept the event.',
            );
        }
        return response()->json($data);
    }

    public function reject_request(Request $request, $id)
    {
        $event = Event::where('id', $request->id)->first();
        $event->event_status = 0;
        $event->save();
        $event_date = Carbon::parse($event->date)->format('M d, Y');
        $user_id = $event->user_id;
        $user = User::find($user_id);
        $device_id = $user->device_id;
        $title = "Event Request Update";

        $notiBody = 'Your request for islamic event on ' . ' ' . $event_date . ' ' . 'has been rejected.';
        $body = 'Your request for islamic event on ' . ' ' . $event_date . ' ' . 'has been rejected.';
        $messageType = "Event Request Update";
        $otherData = "Event Request Update";
        $notificationType = "0";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $data = [
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
        ];
        Notification::create($data);

        return back();
    }
}
