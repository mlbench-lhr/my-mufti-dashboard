<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventScholar;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsAndApptController extends Controller
{
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
        $events = Event::where('event_status', 1)->get();
        return view('frontend.AllEvents', compact('events'));
    }
    public function get_all_events(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = Event::where('event_status', 1)->count();
        $query = Event::with('scholars', 'hosted_by', 'event_questions');

        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'desc');
        $query->where('event_status', 1);
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
            $notifTitle = "Event Request Update";

            $notiBody = 'Your request for ' . $event->event_title . ' on ' . $event_date . ' has been approved.';
            $body = 'Your request for ' . $event->event_title . ' on ' . $event_date . ' has been approved.';
            $message_type = "Event Request Update";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

            $data = [
                'user_id' => $user_data->id,
                'title' => $notifTitle,
                'body' => $body,
            ];
            Notification::create($data);

            // $eventScholars = EventScholar::where('event_id', $request->id)->pluck('user_id')->toArray();
            $eventScholars = EventScholar::where('event_id', $request->id)
                ->pluck('user_id')
                ->filter(function ($value) {
                    return $value != 0;
                })
                ->toArray();
            array_walk($eventScholars, function ($value) use ($event_date, $user_data, $event) {
                $user = User::find($value);
                $device_id = $user->device_id;
                $notifTitle = "You've Been Added to a New Event!";
                $notiBody = $user_data->name . " has invited you to participate as a scholar in their event: " . $event->event_title;
                $body = $user_data->name . " has invited you to participate as a scholar in their event: " . $event->event_title;
                $message_type = "You've Been Added to a New Event!";

                $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

                $data = [
                    'user_id' => $user->id,
                    'title' => $notifTitle,
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
        // return back();
        // echo json_encode($data);
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
        $notifTitle = "Event Request Update";

        $notiBody = 'Your request for islamic event on ' . ' ' . $event_date . ' ' . 'has been rejected.';
        $body = 'Your request for islamic event on ' . ' ' . $event_date . ' ' . 'has been rejected.';
        $message_type = "Event Request Update";

        $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

        $data = [
            'user_id' => $user->id,
            'title' => $notifTitle,
            'body' => $body,
        ];
        Notification::create($data);

        return back();
    }

    // send notification
    public function send_notification($device_id, $notifTitle, $notiBody, $message_type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        // server key
        $serverKey = 'AAAAnAue4jY:APA91bHIxmuujE5JyCVtm9i6rci5o9i3mQpijhqzCCQYUuwLPqwtKSU9q47u3Q2iUDiOaxN7-WMoOH-qChlvSec5rqXW2WthIXaV4lCi4Ps00qmLLFeI-VV8O_hDyqV6OqJRpL1n-k_e';

        $headers = [
            'Content-Type:application/json',
            'Authorization:key=' . $serverKey,
        ];

        // notification content
        $notification = [
            'title' => $notifTitle,
            'body' => $notiBody,
        ];
        // optional
        $dataPayLoad = [
            'to' => '/topics/test',
            'date' => '2019-01-01',
            'other_data' => 'meeting',
            'message_Type' => $message_type,
            // 'notification' => $notification,
        ];

        // create Api body
        $notifbody = [
            'notification' => $notification,
            'data' => $dataPayLoad,
            'time_to_live' => 86400,
            'to' => $device_id,
            // 'registration_ids' => $arr,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notifbody));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);
    }
}
