<?php
namespace App\Http\Controllers\Mufti;

use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Degree;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MuftiExperience extends Controller
{
    public function all_experience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $mufti_experience = Experience::where('user_id', $request->user_id)->get();

        if (count($mufti_experience) <= 0) {
            $response = [
                'status'  => false,
                'message' => 'No experience added yet!',
                'data'    => [],
            ];
            return response()->json($response, 200);
        }

        $response = [
            'status'  => true,
            'message' => "All Experience's!",
            'data'    => $mufti_experience,
        ];
        return response()->json($response, 200);
    }

    public function add_experience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'              => 'required',
            'company_name'         => 'required',
            'experience_startDate' => 'required',
            'is_present'           => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $data = [
            'user_id'              => $request->user_id,
            'company_name'         => $request->company_name,
            'experience_startDate' => $request->experience_startDate,
            'experience_endDate'   => $request->is_present ? '' : $request->experience_endDate ?? '',
        ];

        $experience = Experience::create($data);
        $experience = Experience::where('id', $experience->id)->first();

        $response = [
            'status'  => true,
            'message' => 'Experience added successfully.',
            'data'    => $experience,
        ];
        return response()->json($response, 200);
    }
    public function update_experience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience_id' => 'required|exists:experiences,id',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $experience = Experience::where(['id' => $request->experience_id])->first();

        if (! $experience) {
            return ResponseHelper::jsonResponse(false, 'Experience Not Found');
        }

        $data = [
            'company_name'         => $request->company_name ?? $experience->company_name,
            'experience_startDate' => $request->experience_startDate ?? $experience->experience_startDate,
            'experience_endDate'   => $request->is_present ? '' : $request->experience_endDate ?? $experience->experience_endDate,
        ];

        Experience::where(['id' => $request->experience_id])->update($data);

        $experience = Experience::where(['id' => $request->experience_id])->first();

        $response = [
            'status'  => true,
            'message' => 'Experience updated successfully.',
            'data'    => $experience,
        ];
        return response()->json($response, 200);
    }

    public function delete_experience(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience_id' => 'required|exists:experiences,id',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $experience = Experience::find($request->experience_id);
        if (! $experience) {
            return ResponseHelper::jsonResponse(false, 'Experience Not Found');
        }

        $experience->delete();

        return ResponseHelper::jsonResponse(true, 'Experience deleted successfully.');
    }

}
