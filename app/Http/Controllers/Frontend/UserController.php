<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{Question, User, UserQuery, Interest, MuftiAppointment, UserAllQuery, Degree, Event, Experience, Mufti, Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    public  function all_users()
    {
        $users = User::where('user_type', 'user')->get();
        return view('frontend.AllUsers', compact('users'));
    }
    public function get_all_users(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = User::where('user_type', 'user')->count();
        $query = User::where('user_type', 'user');

        if ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }
        $sortingOption = $request->input('sorting'); // Get the sorting option from the request

        if ($sortingOption === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortingOption === 'earliest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
            $row->posted_questions = Question::where('user_id', $row->id)->count();
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public  function all_scholars()
    {
        $users = User::where('user_type', 'scholar')->get();
        return view('frontend.AllScholars', compact('users'));
    }
    public function get_all_scholars(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = User::where('user_type', 'scholar')->count();
        $query = User::where('user_type', 'scholar');

        if ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'desc');

        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
            $interests = Interest::where('user_id', $row->id)->select('id', 'user_id', 'interest')->get();
            $row->interests = $interests;
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public  function all_scholar_request()
    {
        $users = User::where(['user_type' => 'user', 'mufti_status' => 1])->get();
        return view('frontend.ScholarRequest', compact('users'));
    }
    public function get_all_scholar_request(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = User::where(['user_type' => 'user', 'mufti_status' => 1])->count();
        $query = User::where(['user_type' => 'user', 'mufti_status' => 1]);

        if ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }
        $sortingOption = $request->input('sorting'); // Get the sorting option from the request

        if ($sortingOption === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortingOption === 'earliest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
            $row->posted_questions = Question::where('user_id', $row->id)->count();
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function scholar_request_detail(Request $request, $id)
    {
        $user = User::with('interests', 'mufti_detail')->where('id', $id)->first();
        $degrees = Degree::where('user_id', $id)->get();
        $experience = Experience::where('user_id', $id)->first();

        $start_date = Carbon::parse($experience->experience_startDate);
        $end_date = Carbon::parse($experience->experience_endDate);

        $diff = $end_date->diff($start_date);

        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
        $work_experience = "";

        if ($years > 0) {
            $work_experience .= $years . " year" . ($years > 1 ? "s" : "");
            if ($months > 0 || $days > 0) {
                $work_experience .= " ";
            }
        }

        if ($months > 0) {
            $work_experience .= $months . " month" . ($months > 1 ? "s" : "");
            if ($days > 0) {
                $work_experience .= " and ";
            }
        }

        if ($days > 0) {
            $work_experience .= $days . " day" . ($days > 1 ? "s" : "");
        }
        $response = [
            'user' => $user,
            'degrees' => $degrees,
            'experience' => $work_experience
        ];
        // dd($response);
        return view('frontend.ScholarRequestDetail', compact('response', 'id'));
    }

    public function approve_request(Request $request, $id)
    {
        $mufti = Mufti::where('user_id', $id)->first();
        $user = User::where('id',  $id)->first();
        $user->mufti_status = 2;
        $user->user_type = 'scholar';
        $user->name = $mufti->name;
        $user->phone_number = $mufti->phone_number;
        $user->fiqa = $mufti->fiqa;
        $user->save();

        $device_id = $user->device_id;
        $notifTitle = "Scholar Request Update";

        $notiBody = 'Congrats! Your request for become a scholar has been accepted. You are  a scholar now!!';
        $body = 'Congrats! Your request for become a scholar has been accepted. You are  a scholar now!!';
        $message_type = "Scholar Request Update";

        $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

        $data = [
            'user_id' => $user->id,
            'title' => $notifTitle,
            'body' => $body,
        ];
        Notification::create($data);

        $mufti->delete();

        return redirect('ScholarsRequests');
    }

    public function reject_request(Request $request, $id)
    {
        $user = User::where('id',  $id)->first();
        $user->mufti_status = 3;
        $user->save();
        $degrees = Degree::where('user_id', $id)->delete();
        $experience = Experience::where('user_id', $id)->delete();
        $mufti = Mufti::where('user_id', $id)->delete();
        $interestes = Interest::where('user_id', $id)->delete();

        $device_id = $user->device_id;
        $notifTitle = "Scholar Request Update";

        $notiBody = 'Sorry! Your request to become a scholar has been rejected due to the submission of incorrect information.';
        $body = 'Sorry! Your request to become a scholar has been rejected due to the submission of incorrect information.';
        $message_type = "Scholar Request Update";

        $this->send_notification($device_id, $notifTitle, $notiBody, $message_type);

        $data = [
            'user_id' => $user->id,
            'title' => $notifTitle,
            'body' => $body,
        ];
        Notification::create($data);

        return redirect('ScholarsRequests');
    }

    public function user_detail(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        // posted questions 
        $posted_questions = Question::where('user_id', $id)->get();
        $response = [
            'user' => $user,
            'posted_questions' => $posted_questions
        ];
        return view('frontend.UserDetail', compact('response', 'id'));
    }
    

    public function get_public_questions_posted_by_user(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Question::where('user_id', $request->id);
        $userCount = Question::where('user_id', $request->id)->count();
        if ($searchTerm) {
            $query->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = Carbon::parse($row->created_at)->format('M d, Y');
            $row->total_categories = count($row->question_category);
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function user_detail_private_questons(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        // private questions 
        $posted_questions = UserQuery::where('user_id', $id)->get();
        $response = [
            'user' => $user,
            'posted_questions' => $posted_questions
        ];
        return view('frontend.UserDetailPrivateQuestions', compact('response', 'id'));
    }

    public function get_private_questions_asked_by_user(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = UserQuery::where('user_id', $request->id)->count();
        $query = UserQuery::where('user_id', $request->id)->with('all_question.mufti_detail.interests');

        if ($searchTerm) {
            $query->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function user_detail_appointments(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();

        $user_type = $user->user_type;
        if ($user_type == 'scholar') {
            $appointments = MuftiAppointment::where('mufti_id', $id)->get();
        } else {
            $appointments = MuftiAppointment::where('user_id', $id)->get();
        }


        $response = [
            'user' => $user,
            'appointments' => $appointments
        ];
        return view('frontend.UserDetailAppointments', compact('response', 'id'));
    }

    public function get_appointments_of_user(Request $request)
    {
        $searchTerm = $request->input('search');
        $user = User::where('id', $request->id)->first();
        $user_type = $user->user_type;
        if ($user_type == 'scholar') {
            $userCount = MuftiAppointment::where('mufti_id', $request->id)->count();
            $query = MuftiAppointment::where('mufti_id', $request->id)->with('mufti_detail.interests');
        } else {
            $userCount = MuftiAppointment::where('user_id', $request->id)->count();
            $query = MuftiAppointment::where('user_id', $request->id)->with('mufti_detail.interests');
        }


        if ($searchTerm) {
            $query->whereHas('mufti_detail', function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = Carbon::parse($row->created_at)->format('M d, Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function user_detail_asked_from_me(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();

        $user_type = $user->user_type;
        $askedFromMufti = UserAllQuery::with('user_detail.interests')->where(['mufti_id' => $id])->get();

        $response = [
            'user' => $user,
            'askedFromMufti' => $askedFromMufti
        ];
        return view('frontend.UserDetailAskedFromMe', compact('response', 'id'));
    }

    public function get_asked_from_me(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = UserAllQuery::with('user_detail.interests')->where(['mufti_id' => $request->id])->count();
        $query = UserAllQuery::with('user_detail.interests')->where(['mufti_id' => $request->id]);
        if ($searchTerm) {
            $query->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = Carbon::parse($row->created_at)->format('M d, Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function user_detail_degrees(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        $degrees = Degree::where('user_id', $id)->get();

        $response = [
            'user' => $user,
            'degrees' => $degrees
        ];
        return view('frontend.UserDetailDegrees', compact('response', 'id'));
    }

    public function user_events(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        // posted questions 
        $events = Event::where('user_id', $id)->where('event_status', 1)->get();
        $response = [
            'user' => $user,
            'events' => $events
        ];
        return view('frontend.UserDetailEvents', compact('response', 'id'));
    }

    public function get_user_events(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Event::where('event_status', 1)->where('user_id', $request->id);
        $userCount = Event::where('event_status', 1)->where('user_id', $request->id)->count();
        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->event_date = Carbon::parse($row->date)->format('M d, Y');
            $row->event_time = Carbon::parse($row->date)->format('H:i A');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function user_events_requests(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        // posted questions 
        $events = Event::where('user_id', $id)->get();
        $response = [
            'user' => $user,
            'events' => $events
        ];
        return view('frontend.UserDetailEventsRequests', compact('response', 'id'));
    }

    public function get_user_events_requests(Request $request)
    {
        $searchTerm = $request->input('search');
        $query = Event::where('user_id', $request->id);
        $userCount = Event::where('user_id', $request->id)->count();
        if ($searchTerm) {
            $query->where('event_title', 'LIKE', '%' . $searchTerm . '%');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->event_date = Carbon::parse($row->date)->format('M d, Y');
            $row->event_time = Carbon::parse($row->date)->format('H:i A');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function delete_user(Request $request, $id)
    {
        $user = User::where('id',  $id)->first();
        if ($user->mufti_status == 2) {
            $data = array(
                'status' => 'mufti',
                'message' => 'User deleted successfully.',
            );
        } else {
            $data = array(
                'status' => 'user',
                'message' => 'User deleted successfully.',
            );
        }
        $user->deleteWithRelated();
        $user->delete();
       
        return response()->json($data);
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
