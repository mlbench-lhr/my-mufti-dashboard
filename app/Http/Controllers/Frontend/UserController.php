<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\DeleteAccountRequest;
use App\Models\Event;
use App\Models\Experience;
use App\Models\Interest;
use App\Models\Mufti;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function all_users()
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
        $sortingOption = $request->input('sorting');

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
    public function deletion_requests()
    {
        $userRequests = DeleteAccountRequest::where('status', 1)->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userRequests)->get();
        return view('frontend.DeletionRequests', compact('users'));
    }

    public function get_deletion_requests(Request $request)
    {
        $searchTerm = $request->input('search');
        $sortingOption = $request->input('sorting');

        $deletionRequests = DeleteAccountRequest::where('status', 1)
            ->pluck('updated_at', 'user_id')
            ->toArray();

        $userCount = User::whereIn('id', array_keys($deletionRequests))->count();
        $query = User::whereIn('id', array_keys($deletionRequests));

        if ($searchTerm) {
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        if ($sortingOption === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sortingOption === 'earliest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(10);
        foreach ($users as $user) {
            $user->requested_date = isset($deletionRequests[$user->id])
            ? \Carbon\Carbon::parse($deletionRequests[$user->id])->format('M d, Y')
            : null;
        }

        return response()->json(['userCount' => $userCount, 'users' => $users]);
    }

    public function reject_request_deletion(Request $request)
    {
        $userId = $request->user_id;
        $data = [
            'status' => 3,
            'reason' => $request->reason ?? "",
        ];
        DeleteAccountRequest::where('user_id', $userId)->update($data);

        $user = User::where('id', $userId)->first();

        $device_id = $user->device_id;
        $title = "Deletion Request Update";

        $notiBody = 'We regret to inform you that your account deletion request has been rejected due to pending tasks that need to be completed on your end.';
        $body = 'We regret to inform you that your account deletion request has been rejected due to pending tasks that need to be completed on your end.';
        $messageType = "Deletion Request Update";
        $otherData = "Deletion Request Update";
        $notificationType = "0";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $data = [
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
        ];
        Notification::create($data);

        return redirect('DeletionRequests');
    }

    public function accept_request_deletion($id)
    {
        $data = [
            'status' => 2,
            'reason' => "",
        ];
        DeleteAccountRequest::where('user_id', $id)->update($data);
        DeleteAccountRequest::where('user_id', $id)->delete();

        $user = User::where('id', $id)->first();

        $device_id = $user->device_id;
        $title = "Deletion Request Update";

        $notiBody = 'Congratulations! Your account deletion request has been approved. Your account will now be deleted.';
        $body = 'Congratulations! Your account deletion request has been approved. Your account will now be deleted.';
        $messageType = "Deletion Request Update";
        $otherData = "Deletion Request Update";
        $notificationType = "0";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $user->deleteWithRelated();
        $user->delete();

        return redirect('DeletionRequests');

    }

    public function all_scholars()
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

    public function all_scholar_request()
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
        $sortingOption = $request->input('sorting');

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
            'experience' => $work_experience,
        ];
        return view('frontend.ScholarRequestDetail', compact('response', 'id'));
    }
    public function approve_request(Request $request, $id)
    {
        $mufti = Mufti::where('user_id', $id)->first();
        $user = User::where('id', $id)->first();
        $user->mufti_status = 2;
        $user->user_type = 'scholar';
        $user->name = $mufti->name;
        $user->phone_number = $mufti->phone_number;
        $user->fiqa = $mufti->fiqa;
        $user->save();

        $device_id = $user->device_id;
        $title = "Scholar Request Update";

        $notiBody = 'Congrats! Your request for become a scholar has been accepted. You are  a scholar now!!';
        $body = 'Congrats! Your request for become a scholar has been accepted. You are  a scholar now!!';
        $messageType = "Scholar Request Update";
        $otherData = "Scholar Request Update";
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

        $mufti->delete();

        return redirect('ScholarsRequests');
    }
    public function reject_request(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $user->mufti_status = 3;
        $user->save();
        $degrees = Degree::where('user_id', $id)->delete();
        $experience = Experience::where('user_id', $id)->delete();
        $mufti = Mufti::where('user_id', $id)->delete();
        $interestes = Interest::where('user_id', $id)->delete();

        $device_id = $user->device_id;
        $title = "Scholar Request Update";

        $notiBody = 'Sorry! Your request to become a scholar has been rejected due to the submission of incorrect information.';
        $body = 'Sorry! Your request to become a scholar has been rejected due to the submission of incorrect information.';
        $messageType = "Scholar Request Update";
        $otherData = "Scholar Request Update";
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

        return redirect('ScholarsRequests');
    }
    public function user_detail(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        $posted_questions = Question::where('user_id', $id)->get();
        $response = [
            'user' => $user,
            'posted_questions' => $posted_questions,
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
        $posted_questions = UserQuery::where('user_id', $id)->get();
        $response = [
            'user' => $user,
            'posted_questions' => $posted_questions,
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
        $query->orderBy('created_at', 'DESC');

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
            'appointments' => $appointments,
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
            'askedFromMufti' => $askedFromMufti,
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
        $query->orderBy('created_at', 'DESC');

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
            'degrees' => $degrees,
        ];
        return view('frontend.UserDetailDegrees', compact('response', 'id'));
    }
    public function user_events(Request $request, $id)
    {
        $user = User::with('interests')->where('id', $id)->first();
        $events = Event::where('user_id', $id)->where('event_status', 1)->get();
        $response = [
            'user' => $user,
            'events' => $events,
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
        $events = Event::where('user_id', $id)->get();
        $response = [
            'user' => $user,
            'events' => $events,
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

        $user = User::where('id', $id)->first();

        if ($id == 9 || $id == "9") {
            $data = array(
                'status' => 'mufti',
                'message' => 'You cannot delete default mufti.',
            );
        } else {
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
        }

        return response()->json($data);
    }
}
