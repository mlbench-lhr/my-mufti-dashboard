<?php

namespace App\Http\Controllers\Mufti;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MuftiDegrees extends Controller
{
    public function mufti_all_degrees(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $mufti_degree = Degree::where('user_id', $request->user_id)->get();

        if (count($mufti_degree) <= 0) {
            $response = [
                'status' => false,
                'message' => 'No degrees added yet!',
                'data' => [],
            ];
            return response()->json($response, 200);
        }

        $response = [
            'status' => true,
            'message' => 'All degrees!',
            'data' => $mufti_degree,
        ];
        return response()->json($response, 200);
    }
    public function get_single_degree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'degree_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();
        $degree = Degree::where(['id' => $request->degree_id, 'user_id' => $request->user_id])->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }
        if (!$degree) {
            return ResponseHelper::jsonResponse(false, 'Degree Not Found');
        }

        $response = [
            'status' => true,
            'message' => 'Mufti degree!',
            'data' => $degree,
        ];
        return response()->json($response, 200);
    }
    public function add_degree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'degree_title' => 'required',
            'institute_name' => 'required',
            'degree_startDate' => 'required',
            'degree_endDate' => 'required',
            'degree_image' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $data = [
            'user_id' => $request->user_id,
            'degree_image' => $request->degree_image,
            'degree_title' => $request->degree_title,
            'institute_name' => $request->institute_name,
            'degree_startDate' => $request->degree_startDate,
            'degree_endDate' => $request->degree_endDate,
        ];
        $base64File = $data['degree_image'];

        $fileData = base64_decode($base64File);

        $name = 'degree_images/' . Str::random(15) . '.png';

        Storage::put('public/' . $name, $fileData);

        $data['degree_image'] = $name;
        $degree = Degree::create($data);

        $degree = Degree::where('id', $degree->id)->first();

        $response = [
            'status' => true,
            'message' => 'Mufti degree!',
            'data' => $degree,
        ];
        return response()->json($response, 200);
    }
    public function update_degree(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'degree_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();
        $degree = Degree::where(['id' => $request->degree_id, 'user_id' => $request->user_id])->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }
        if (!$degree) {
            return ResponseHelper::jsonResponse(false, 'Degree Not Found');
        }

        if ($request->degree_image == "") {
            $image = $degree->degree_image;
        } else {
            $base64File = $request->degree_image;

            $fileData = base64_decode($base64File);

            $name = 'degree_images/' . Str::random(15) . '.png';

            Storage::put('public/' . $name, $fileData);
            $image = $name;

        }
        $data = [
            'degree_image' => $image,
            'degree_title' => $request->degree_title ?? $degree->degree_title,
            'institute_name' => $request->institute_name ?? $degree->institute_name,
            'degree_startDate' => $request->degree_startDate ?? $degree->degree_startDate,
            'degree_endDate' => $request->degree_endDate ?? $degree->degree_endDate,
        ];
        Degree::where(['id' => $request->degree_id, 'user_id' => $request->user_id])->update($data);
        $degree = Degree::where(['id' => $request->degree_id, 'user_id' => $request->user_id])->first();

        $response = [
            'status' => true,
            'message' => 'Update degree Successfully!',
            'data' => $degree,
        ];
        return response()->json($response, 200);
    }
}
