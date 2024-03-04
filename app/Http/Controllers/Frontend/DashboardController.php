<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Event;
use App\Models\MuftiAppointment;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAllQuery;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $get_all_users = $this->get_all_users();
        $get_all_events = $this->get_all_events();
        $get_questions = $this->get_questions();
        $activities = $this->get_all_activities();
        $get_scholars = $this->get_scholars();
        $get_all_appoinments = $this->get_all_appoinments();
        $get_all_questions = $this->get_all_questions();
        $apple = User::where('a_code', '!=', '')->count();
        $google = User::where('g_code', '!=', '')->count();
        $inApp = User::where('g_code', '=', '')->where('a_code', '=', '')->count();
        $accountCount = User::count();
        if($accountCount == 0){
            $apple = 0;
            $google = 0;
            $inApp = 0;
        }else{
            $apple = floor((($apple / $accountCount) * 100));
            $google = floor((($google / $accountCount) * 100));
            $inApp = floor((($inApp / $accountCount) * 100));
        }
      
        
        $appointmentCount = MuftiAppointment::count();
        $questionCount = UserAllQuery::count();
        if ($questionCount != 0) {
            $count = floor((($get_all_questions['all_accepted']) / $questionCount) * 100);
        }

        $response = [
            'count' => $count ?? 0,
            'countActivities' => $activities['countActivities'],
            'activities' => $activities['activities'],
            'get_all_users' => $get_all_users,
            'get_all_events' => $get_all_events,
            'get_questions' => $get_questions,
            'get_scholars' => $get_scholars,
            'get_all_appoinments' => $get_all_appoinments,
            'get_all_questions' => $get_all_questions,
            'accountCount' => $accountCount ?? 0,
            'questionCount' => $questionCount ?? 0,
            'apple' => $apple ?? 0,
            'google' => $google ?? 0,
            'inApp' => $inApp ?? 0,
            'appointmentCount' => $appointmentCount ?? 0,

        ];
        return view('frontend.Dashboard', compact('response'));
    }
    public function get_all_users()
    {

        $all_users = User::select('created_at')->where('user_type', 'user')->get();
        $month = [];
        foreach ($all_users as $value) {
            $month[] = Carbon::now()->diffInMonths($value->created_at);
        }

        $dt = Carbon::now();
        $month_name = [
            'current' => $dt->format('M'),
            'one_before' => $dt->subMonths(1)->format('M'),
            'two_before' => $dt->subMonths(1)->format('M'),
            'three_before' => $dt->subMonths(1)->format('M'),
            'four_before' => $dt->subMonths(1)->format('M'),
            'five_before' => $dt->subMonths(1)->format('M'),
            'six_before' => $dt->subMonths(1)->format('M'),
            'seven_before' => $dt->subMonths(1)->format('M'),
            'eight_before' => $dt->subMonths(1)->format('M'),
            'nine_before' => $dt->subMonths(1)->format('M'),
            'ten_before' => $dt->subMonths(1)->format('M'),
            'eleven_before' => $dt->subMonths(1)->format('M'),
        ];

        $current_month = $one_month_before = $two_months_before = $three_months_before = $four_months_before = $five_months_before = [];

        foreach ($month as $value) {
            if ($value == 0) {
                $current_month[] = $value;
            } else if ($value == 1) {
                $one_month_before[] = $value;
            } else if ($value == 2) {
                $two_months_before[] = $value;
            } else if ($value == 3) {
                $three_months_before[] = $value;
            } else if ($value == 4) {
                $four_months_before[] = $value;
            } else if ($value == 5) {
                $five_months_before[] = $value;
            }
        }

        $all_users = count($all_users);
        if ($all_users > 0) {
            $data = [
                'total_users' => $all_users,
                'month_name' => $month_name,
                'current_month' => round(((count($current_month) / $all_users) * 100), 0),
                'one_month_before' => round(((count($one_month_before) / $all_users) * 100), 0),
                'two_months_before' => round(((count($two_months_before) / $all_users) * 100), 0),
                'three_months_before' => round(((count($three_months_before) / $all_users) * 100), 0),
                'four_months_before' => round(((count($four_months_before) / $all_users) * 100), 0),
                'five_months_before' => round(((count($five_months_before) / $all_users) * 100), 0),
            ];
        } else {
            $data = [
                'total_users' => 0,
                'month_name' => $month_name,
                'current_month' => 0,
                'one_month_before' => 0,
                'two_months_before' => 0,
                'three_months_before' => 0,
                'four_months_before' => 0,
                'five_months_before' => 0,
            ];
        }

        return $data;
    }
    public function get_all_events()
    {

        $all_events = Event::select('updated_at')->where('event_status', 1)->get();
        $month = [];
        foreach ($all_events as $value) {
            $month[] = Carbon::now()->diffInMonths($value->updated_at);
        }

        $dt = Carbon::now();
        $month_name = [
            'current' => $dt->format('M'),
            'one_before' => $dt->subMonths(1)->format('M'),
            'two_before' => $dt->subMonths(1)->format('M'),
            'three_before' => $dt->subMonths(1)->format('M'),
            'four_before' => $dt->subMonths(1)->format('M'),
            'five_before' => $dt->subMonths(1)->format('M'),
            'six_before' => $dt->subMonths(1)->format('M'),
            'seven_before' => $dt->subMonths(1)->format('M'),
            'eight_before' => $dt->subMonths(1)->format('M'),
            'nine_before' => $dt->subMonths(1)->format('M'),
            'ten_before' => $dt->subMonths(1)->format('M'),
            'eleven_before' => $dt->subMonths(1)->format('M'),
        ];

        $current_month = $one_month_before = $two_months_before = $three_months_before = $four_months_before = $five_months_before = [];

        foreach ($month as $value) {
            if ($value == 0) {
                $current_month[] = $value;
            } else if ($value == 1) {
                $one_month_before[] = $value;
            } else if ($value == 2) {
                $two_months_before[] = $value;
            } else if ($value == 3) {
                $three_months_before[] = $value;
            } else if ($value == 4) {
                $four_months_before[] = $value;
            } else if ($value == 5) {
                $five_months_before[] = $value;
            }
        }

        $all_events = count($all_events);
        if ($all_events > 0) {
            $data = [
                'total_events' => $all_events,
                'month_name' => $month_name,
                'current_month' => round(((count($current_month) / $all_events) * 100), 0),
                'one_month_before' => round(((count($one_month_before) / $all_events) * 100), 0),
                'two_months_before' => round(((count($two_months_before) / $all_events) * 100), 0),
                'three_months_before' => round(((count($three_months_before) / $all_events) * 100), 0),
                'four_months_before' => round(((count($four_months_before) / $all_events) * 100), 0),
                'five_months_before' => round(((count($five_months_before) / $all_events) * 100), 0),
            ];
        } else {
            $data = [
                'total_events' => 0,
                'month_name' => $month_name,
                'current_month' => 0,
                'one_month_before' => 0,
                'two_months_before' => 0,
                'three_months_before' => 0,
                'four_months_before' => 0,
                'five_months_before' => 0,
            ];
        }

        return $data;
    }
    public function get_questions()
    {

        $all_questions = Question::select('created_at')->get();
        $month = [];
        foreach ($all_questions as $value) {
            $month[] = Carbon::now()->diffInMonths($value->created_at);
        }

        $current_month_match = $one_month_before_match = $two_months_before_match = $three_months_before_match = $four_months_before_match = $five_months_before_match = $six_months_before_match = $seven_months_before_match = $eight_months_before_match = $nine_months_before_match = $ten_months_before_match = $eleven_months_before_match = [];

        foreach ($month as $value) {
            if ($value == 0) {
                $current_month_match[] = $value;
            } else if ($value == 1) {
                $one_month_before_match[] = $value;
            } else if ($value == 2) {
                $two_months_before_match[] = $value;
            } else if ($value == 3) {
                $three_months_before_match[] = $value;
            } else if ($value == 4) {
                $four_months_before_match[] = $value;
            } else if ($value == 5) {
                $five_months_before_match[] = $value;
            } else if ($value == 6) {
                $six_months_before_match[] = $value;
            } else if ($value == 7) {
                $seven_months_before_match[] = $value;
            } else if ($value == 8) {
                $eight_months_before_match[] = $value;
            } else if ($value == 9) {
                $nine_months_before_match[] = $value;
            } else if ($value == 10) {
                $ten_months_before_match[] = $value;
            } else if ($value == 11) {
                $eleven_months_before_match[] = $value;
            }
        }

        $all_questions = count($all_questions);

        if ($all_questions == 0) {
            $data = [
                'total_matches' => $all_questions,
                'current_month_match' => 0,
                'one_month_before_match' =>  0,
                'two_months_before_match' =>  0,
                'three_months_before_match' =>  0,
                'four_months_before_match' =>  0,
                'five_months_before_match' =>  0,
                'six_months_before_match' =>  0,
                'seven_months_before_match' =>  0,
                'eight_months_before_match' =>  0,
                'nine_months_before_match' =>  0,
                'ten_months_before_match' =>  0,
                'eleven_months_before_match' =>  0,
            ];
        } else {
            $data = [
                'total_matches' => $all_questions,
                'current_month_match' => round(((count($current_month_match) / $all_questions) * 100), 0) ?? 0,
                'one_month_before_match' => round(((count($one_month_before_match) / $all_questions) * 100), 0) ?? 0,
                'two_months_before_match' => round(((count($two_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'three_months_before_match' => round(((count($three_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'four_months_before_match' => round(((count($four_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'five_months_before_match' => round(((count($five_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'six_months_before_match' => round(((count($six_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'seven_months_before_match' => round(((count($seven_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'eight_months_before_match' => round(((count($eight_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'nine_months_before_match' => round(((count($nine_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'ten_months_before_match' => round(((count($ten_months_before_match) / $all_questions) * 100), 0) ?? 0,
                'eleven_months_before_match' => round(((count($eleven_months_before_match) / $all_questions) * 100), 0) ?? 0,
            ];
        }


        return $data;
    }
    public function get_scholars()
    {

        $all_scholars = User::select('created_at')->where('user_type', 'scholar')->get();
        $month = [];
        foreach ($all_scholars as $value) {
            $month[] = Carbon::now()->diffInMonths($value->created_at);
        }

        $current_month_match = $one_month_before_match = $two_months_before_match = $three_months_before_match = $four_months_before_match = $five_months_before_match = $six_months_before_match = $seven_months_before_match = $eight_months_before_match = $nine_months_before_match = $ten_months_before_match = $eleven_months_before_match = [];

        foreach ($month as $value) {
            if ($value == 0) {
                $current_month_match[] = $value;
            } else if ($value == 1) {
                $one_month_before_match[] = $value;
            } else if ($value == 2) {
                $two_months_before_match[] = $value;
            } else if ($value == 3) {
                $three_months_before_match[] = $value;
            } else if ($value == 4) {
                $four_months_before_match[] = $value;
            } else if ($value == 5) {
                $five_months_before_match[] = $value;
            } else if ($value == 6) {
                $six_months_before_match[] = $value;
            } else if ($value == 7) {
                $seven_months_before_match[] = $value;
            } else if ($value == 8) {
                $eight_months_before_match[] = $value;
            } else if ($value == 9) {
                $nine_months_before_match[] = $value;
            } else if ($value == 10) {
                $ten_months_before_match[] = $value;
            } else if ($value == 11) {
                $eleven_months_before_match[] = $value;
            }
        }

        $all_scholars = count($all_scholars);

        if ($all_scholars == 0) {
            $data = [
                'total_matches' => $all_scholars,
                'current_month_match' =>  0,
                'one_month_before_match' =>  0,
                'two_months_before_match' =>  0,
                'three_months_before_match' =>  0,
                'four_months_before_match' =>  0,
                'five_months_before_match' =>  0,
                'six_months_before_match' =>  0,
                'seven_months_before_match' =>  0,
                'eight_months_before_match' =>  0,
                'nine_months_before_match' =>  0,
                'ten_months_before_match' =>  0,
                'eleven_months_before_match' =>  0,
            ];
        } else {
            $data = [
                'total_matches' => $all_scholars,
                'current_month_match' => round(((count($current_month_match) / $all_scholars) * 100), 0) ?? 0,
                'one_month_before_match' => round(((count($one_month_before_match) / $all_scholars) * 100), 0) ?? 0,
                'two_months_before_match' => round(((count($two_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'three_months_before_match' => round(((count($three_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'four_months_before_match' => round(((count($four_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'five_months_before_match' => round(((count($five_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'six_months_before_match' => round(((count($six_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'seven_months_before_match' => round(((count($seven_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'eight_months_before_match' => round(((count($eight_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'nine_months_before_match' => round(((count($nine_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'ten_months_before_match' => round(((count($ten_months_before_match) / $all_scholars) * 100), 0) ?? 0,
                'eleven_months_before_match' => round(((count($eleven_months_before_match) / $all_scholars) * 100), 0) ?? 0,
            ];
        }


        return $data;
    }
    public function get_all_activities()
    {

        $data = [
            'activities' => Activity::orderBy('created_at', 'desc')->skip(0)->take(12)->get(),
            'countActivities' => count(Activity::all()),
        ];

        return $data;
    }
    public function get_all_appoinments()
    {

        $all_appointments = MuftiAppointment::select('created_at')->get();
        $month = [];

        foreach ($all_appointments as $value) {
            $month[] = Carbon::now()->diffInMonths($value->created_at);
        }

        $dt = Carbon::now();
        $month_name = [
            'current' => $dt->format('M'),
            'one_before' => $dt->subMonths(1)->format('M'),
            'two_before' => $dt->subMonths(1)->format('M'),
            'three_before' => $dt->subMonths(1)->format('M'),
            'four_before' => $dt->subMonths(1)->format('M'),
            'five_before' => $dt->subMonths(1)->format('M'),
            'six_before' => $dt->subMonths(1)->format('M'),
            'seven_before' => $dt->subMonths(1)->format('M'),
            'eight_before' => $dt->subMonths(1)->format('M'),
            'nine_before' => $dt->subMonths(1)->format('M'),
            'ten_before' => $dt->subMonths(1)->format('M'),
            'eleven_before' => $dt->subMonths(1)->format('M'),
        ];

        $current_month = $one_month_before = $two_months_before = $three_months_before = $four_months_before =
            $five_months_before = $six_months_before = [];

        foreach ($month as $value) {
            if ($value == 0) {
                $current_month[] = $value;
            } else if ($value == 1) {
                $one_month_before[] = $value;
            } else if ($value == 2) {
                $two_months_before[] = $value;
            } else if ($value == 3) {
                $three_months_before[] = $value;
            } else if ($value == 4) {
                $four_months_before[] = $value;
            } else if ($value == 5) {
                $five_months_before[] = $value;
            } else if ($value == 6) {
                $six_months_before[] = $value;
            }
        }

        $all_appointments = count($all_appointments);

        if ($all_appointments > 0) {
            $data = [
                'total_appointments' => $all_appointments,
                'month_name' => $month_name,
                'current_month' => round(((count($current_month) / $all_appointments) * 100), 0),
                'one_month_before' => round(((count($one_month_before) / $all_appointments) * 100), 0),
                'two_months_before' => round(((count($two_months_before) / $all_appointments) * 100), 0),
                'three_months_before' => round(((count($three_months_before) / $all_appointments) * 100), 0),
                'four_months_before' => round(((count($four_months_before) / $all_appointments) * 100), 0),
                'five_months_before' => round(((count($five_months_before) / $all_appointments) * 100), 0),
                'six_months_before' => round(((count($six_months_before) / $all_appointments) * 100), 0),
            ];
        } else {
            $data = [
                'total_users' => 0,
                'month_name' => $month_name,
                'current_month' => 0,
                'one_month_before' => 0,
                'two_months_before' => 0,
                'three_months_before' => 0,
                'four_months_before' => 0,
                'five_months_before' => 0,
                'six_months_before' => 0,
            ];
        }

        return $data;
    }
    public function get_all_questions()
    {

        $all_accepted = UserAllQuery::select('updated_at')->where('status', 1)->get();
        // dd($all_accepted);
        $all_rejected = UserAllQuery::select('updated_at')->where('status', 2)->get();
        $month = [];
        $accepted = [];
        foreach ($all_accepted as $value) {
            $accepted[] = Carbon::now()->diffInMonths($value->updated_at);
        }
        foreach ($all_rejected as $value) {
            $month[] = Carbon::now()->diffInMonths($value->updated_at);
        }

        $current_month = $one_month_before = $two_months_before = $three_months_before = $four_months_before =
            $five_months_before = $six_months_before = [];
        $current_month1 = $one_month_before1 = $two_months_before1 = $three_months_before1 = $four_months_before1 =
            $five_months_before1 = $six_months_before1 = [];

        foreach ($month as $value) {
            if ($value == 0) {
                $current_month[] = $value;
            } else if ($value == 1) {
                $one_month_before[] = $value;
            } else if ($value == 2) {
                $two_months_before[] = $value;
            } else if ($value == 3) {
                $three_months_before[] = $value;
            } else if ($value == 4) {
                $four_months_before[] = $value;
            } else if ($value == 5) {
                $five_months_before[] = $value;
            } else if ($value == 6) {
                $six_months_before[] = $value;
            }
        }

        foreach ($accepted as $value) {
            if ($value == 0) {
                $current_month1[] = $value;
            } else if ($value == 1) {
                $one_month_before1[] = $value;
            } else if ($value == 2) {
                $two_months_before1[] = $value;
            } else if ($value == 3) {
                $three_months_before1[] = $value;
            } else if ($value == 4) {
                $four_months_before1[] = $value;
            } else if ($value == 5) {
                $five_months_before1[] = $value;
            } else if ($value == 6) {
                $six_months_before1[] = $value;
            }
        }

        $all_accepted = count($all_accepted);
        $all_rejected = count($all_rejected);

        if ($all_accepted > 0 && $all_rejected > 0) {

            $data = [
                'all_accepted' => $all_accepted,
                'all_rejected' => $all_rejected,

                'current_month' => round(((count($current_month) / $all_accepted) * 100), 0),
                'one_month_before' => round(((count($one_month_before) / $all_accepted) * 100), 0),
                'two_months_before' => round(((count($two_months_before) / $all_accepted) * 100), 0),
                'three_months_before' => round(((count($three_months_before) / $all_accepted) * 100), 0),
                'four_months_before' => round(((count($four_months_before) / $all_accepted) * 100), 0),
                'five_months_before' => round(((count($five_months_before) / $all_accepted) * 100), 0),
                'six_months_before' => round(((count($six_months_before) / $all_accepted) * 100), 0),

                'current_month1' => round(((count($current_month1) / $all_rejected) * 100), 0),
                'one_month_before1' => round(((count($one_month_before1) / $all_rejected) * 100), 0),
                'two_months_before1' => round(((count($two_months_before1) / $all_rejected) * 100), 0),
                'three_months_before1' => round(((count($three_months_before1) / $all_rejected) * 100), 0),
                'four_months_before1' => round(((count($four_months_before1) / $all_rejected) * 100), 0),
                'five_months_before1' => round(((count($five_months_before1) / $all_rejected) * 100), 0),
                'six_months_before1' => round(((count($six_months_before1) / $all_rejected) * 100), 0),
            ];
        } else {
            $data = [
                'all_accepted' => 0,
                'all_rejected' => 0,
                'current_month' => 0,
                'one_month_before' => 0,
                'two_months_before' => 0,
                'three_months_before' => 0,
                'four_months_before' => 0,
                'five_months_before' => 0,
                'six_months_before' => 0,
                'current_month1' => 0,
                'one_month_before1' => 0,
                'two_months_before1' => 0,
                'three_months_before1' => 0,
                'four_months_before1' => 0,
                'five_months_before1' => 0,
                'six_months_before1' => 0,

            ];
        }

        return $data;
    }
}
