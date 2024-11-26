<?php

namespace App\Http\Controllers\Mufti;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterMufti;
use App\Models\Degree;
use App\Models\Experience;
use App\Models\Interest;
use App\Models\Mufti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MuftiController extends Controller
{
    public function request_to_become_mufti(RegisterMufti $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $check_user1 = Mufti::where(['user_id' => $user_id, 'status' => 1])->first();
        if ($check_user1) {
            return ResponseHelper::jsonResponse(false, 'Already send a request');
        }

        $check_user2 = Mufti::where(['user_id' => $user_id, 'status' => 2])->first();
        if ($check_user2) {
            return ResponseHelper::jsonResponse(false, 'Already Mufti');
        }

        $data2 = [
            'user_id' => $request->user_id,
            'degree_image' => $request->degree_image,
            'degree_title' => $request->degree_title,
            'institute_name' => $request->institute_name,
            'degree_startDate' => $request->degree_startDate,
            'degree_endDate' => $request->degree_endDate,
        ];
        $base64File = $data2['degree_image'];
        $fileData = base64_decode($base64File);
        $name = 'degree_images/' . Str::random(15) . '.png';
        Storage::put('public/' . $name, $fileData);
        $data2['degree_image'] = $name;

        $data3 = [
            'user_id' => $request->user_id,
            'experience_startDate' => $request->experience_startDate,
            'experience_endDate' => $request->experience_endDate,
        ];

        User::where('id', $request->user_id)->update(['mufti_status' => 1]);

        $data1 = [
            'user_id' => $request->user_id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'fiqa' => $request->fiqa,
            'reason' => "",
            'status' => 1,
        ];

        Mufti::updateOrCreate(
            ['user_id' => $request->user_id],
            $data1
        );

        Degree::create($data2);
        Experience::create($data3);

        collect($request->interest)->map(function ($value) use ($request) {
            return [
                'user_id' => $request->user_id,
                'interest' => $value,
                'fiqa' => $request->fiqa,
            ];
        })->each(function ($data) {
            Interest::create($data);
        });

        $user_data = User::where('id', $request->user_id)->first();
        if ($user_data->mufti_status == 2) {
            $interests = Interest::where('user_id', $user_data->id)->select('id', 'user_id', 'interest')->get();
            $user_data->interests = $interests;
        } else {
            $user_data->interests = [];
        }

        $rejectionReason = "";
        if ($user->mufti_status == 3) {
            $mufti = Mufti::where('user_id', $user->id)->first();
            $rejectionReason = $mufti ? $mufti->reason : "";
        }

        $userArray = $user->toArray();

        $keys = array_keys($userArray);
        $index = array_search('mufti_status', $keys) + 1;
        $userArray = array_merge(
            array_slice($userArray, 0, $index),
            ['reason' => $rejectionReason],
            array_slice($userArray, $index)
        );

        $user_id = $user->id;
        $message = "User " . $user->name . " added scholar’s details.";
        $type = "request";

        ActivityHelper::store_avtivity($user_id, $message, $type);

        $response = [
            'status' => true,
            'message' => 'Request Send Successfully!',
            'data' => $userArray,
        ];
        return response()->json($response, 200);
    }

    public function search_scholar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user_id = $request->user_id;
        $user = User::where(['id' => $request->user_id])->first();
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $search = $request->search;

        $excludedUserId = [$request->user_id];

        $muftis = User::with('interests')->where('name', 'LIKE', '%' . $search . '%')->where('id', 9)->where('mufti_status', 2)->whereNotIn('id', $excludedUserId)->get();

        if (count($muftis) <= 0) {
            return response()->json([
                "message" => "No scholar Found against this search",
                "status" => false,
                "data" => [],
            ], 200);
        } else {
            $response = [
                'message' => 'All scholar according to this search',
                'status' => true,
                'data' => $muftis,
            ];
            return response()->json($response, 200);
        }
    }
}
