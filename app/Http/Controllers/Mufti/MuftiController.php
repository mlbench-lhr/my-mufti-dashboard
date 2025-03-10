<?php
namespace App\Http\Controllers\Mufti;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterMufti;
use App\Http\Requests\RegisterMuftiRequest;
use App\Http\Requests\AddMediaRequest;
use App\Models\Degree;
use App\Models\Experience;
use App\Models\Interest;
use App\Models\Mufti;
use App\Models\Stage;
use App\Models\TempMedia;
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
        $user    = User::find($user_id);
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $existingMufti = Mufti::where('user_id', $request->user_id)
            ->whereIn('status', [1, 2])
            ->whereIn('user_type', ['scholar', 'lifecoach'])
            ->first();

        if ($existingMufti) {
            $message = $existingMufti->status === 1
            ? 'Already sent a request'
            : ($existingMufti->user_type === 'scholar' ? 'Already Mufti' : 'Already Life Coach');
            return ResponseHelper::jsonResponse(false, $message);
        }

        $data2 = [
            'user_id'          => $request->user_id,
            'degree_image'     => $request->degree_image,
            'degree_title'     => $request->degree_title,
            'institute_name'   => $request->institute_name,
            'degree_startDate' => $request->degree_startDate,
            'degree_endDate'   => $request->degree_endDate,
        ];
        $base64File = $data2['degree_image'];
        $fileData   = base64_decode($base64File);
        $name       = 'degree_images/' . Str::random(15) . '.png';
        Storage::put('public/' . $name, $fileData);
        $data2['degree_image'] = $name;

        $data3 = [
            'user_id'              => $request->user_id,
            'company_name'         => '',
            'experience_startDate' => $request->experience_startDate,
            'experience_endDate'   => $request->experience_endDate,
        ];

        User::where('id', $request->user_id)->update(['mufti_status' => 1]);

        $data1 = [
            'user_id'      => $request->user_id,
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'fiqa'         => $request->fiqa ?? '',
            'reason'       => "",
            'status'       => 1,
            'user_type'    => $request->join_as ?? 'scholar',
        ];

        Mufti::updateOrCreate(
            ['user_id' => $request->user_id],
            $data1
        );

        Degree::create($data2);
        Experience::create($data3);

        collect($request->interest)->map(function ($value) use ($request) {
            return [
                'user_id'  => $request->user_id,
                'interest' => $value,
                'fiqa'     => $request->fiqa ?? '',
            ];
        })->each(function ($data) {
            Interest::create($data);
        });

        $user = User::where('id', $request->user_id)->first();

        // if ($user->mufti_status == 2 || $user->mufti_status == 4) {
        //     $interests = Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get();
        //     $user->interests = $interests;
        // } else {
        //     $user->interests = [];
        // }

        $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
        ? Interest::where('user_id', $user->id)->select('id', 'user_id', 'interest')->get()
        : [];

        $rejectionReason = "";
        if ($user->mufti_status == 3) {
            $mufti           = Mufti::where('user_id', $user->id)->first();
            $rejectionReason = $mufti ? $mufti->reason : "";
        }

        $userArray = $user->toArray();
        $keys      = array_keys($userArray);
        $index     = array_search('mufti_status', $keys) + 1;
        $userArray = array_merge(
            array_slice($userArray, 0, $index),
            ['reason' => $rejectionReason],
            array_slice($userArray, $index)
        );

        $userType = $request->join_as === 'lifecoach' ? 'life coach' : 'scholar’s';
        ActivityHelper::store_avtivity(
            $user->id,
            "User {$user->name} added {$userType} details.",
            'request'
        );

        $response = [
            'status'  => true,
            'message' => 'Request Send Successfully!',
            'data'    => $userArray,
        ];
        return response()->json($response, 200);
    }

    public function get_TempID(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
    
        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
    
        $user = User::find($request->user_id);
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
    
        // Remove existing record if found
        $alreadyExist = Stage::where('user_id', $request->user_id)->first();
        if ($alreadyExist) {
            TempMedia::where('temp_id', $alreadyExist->id)->delete();
            Stage::where('user_id', $request->user_id)->delete();
        }
    
        // Create new record
        $stage = Stage::create([
            'user_id' => $request->user_id,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Stage ID generated successfully!',
            'temp_id' => $stage->id,
        ], 200);
    }
    public function add_media_file(AddMediaRequest $request)
{
    $task = Stage::find($request->temp_id);

    if (! $task) {
        return ResponseHelper::jsonResponse(false, 'Temporary Data Not Found');
    }

    $url = null;

    if ($request->hasFile('media')) {
        $file = $request->file('media');
        $extension = strtolower($file->getClientOriginalExtension());

        // Allowed image extensions
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        if (!in_array($extension, $imageExtensions)) {
            return ResponseHelper::jsonResponse(false, 'Invalid file format! Only images are allowed.');
        }

        $imageName = 'degree_images/' . Str::random(15) . '.' . $extension;

        // Store file in the 'public' disk
        $file->storeAs('public', $imageName);

        // Set image path
        $url = $imageName;
    } else {
        return ResponseHelper::jsonResponse(false, 'No image file uploaded.');
    }

    // Convert is_present to boolean
    $is_present = filter_var($request->input('is_present', false), FILTER_VALIDATE_BOOLEAN);

    // Save media record with degree details
    $data = [
        'temp_id'         => $request->temp_id,
        'degree_title'    => $request->input('degree_title'),
        'institute_name'  => $request->input('institute_name'),
        'degree_startDate'=> $request->input('degree_startDate'),
        'degree_endDate' => $request->is_present ? '':$request->degree_endDate ?? $degree->degree_endDate,
        'media'           => $url,
        //'media_type'      => 'image',
        'is_present'      => $is_present,  // Add this field for response clarity
    ];

    TempMedia::create($data);

    // Fetch only related media records
    $temporary_data = TempMedia::where('temp_id', $request->temp_id)
        ->get(['id', 'temp_id', 'media as degree_image', 'degree_title', 'institute_name', 'degree_startDate', 'degree_endDate'])
        ->map(function ($item) {
            return [
                'id'              => $item->id,
                'temp_id'         => $item->temp_id,
                'degree_image'    => $item->degree_image,
                'degree_title'    => $item->degree_title,
                'institute_name'  => $item->institute_name,
                'degree_startDate'=> $item->degree_startDate,
                'degree_endDate'  => $item->degree_endDate,
                'is_present'      => empty($item->degree_endDate), // Dynamically calculate is_present
            ];
        });

    return ResponseHelper::jsonResponseWithData(true, 'Image Uploaded Successfully!', [
        'media' => $temporary_data,
    ]);
}


public function request_to_become_mufti_update(RegisterMuftiRequest $request)
{
    $user_id = $request->user_id;
    $user = User::find($user_id);

    if (!$user) {
        return ResponseHelper::jsonResponse(false, 'User Not Found');
    }
    $existingMufti = Mufti::where('user_id', $request->user_id)
            ->whereIn('status', [1, 2])
            ->whereIn('user_type', ['scholar', 'lifecoach'])
            ->first();

        if ($existingMufti) {
            $message = $existingMufti->status === 1
            ? 'Already sent a request'
            : ($existingMufti->user_type === 'scholar' ? 'Already Mufti' : 'Already Life Coach');
            return ResponseHelper::jsonResponse(false, $message);
        }

        $degrees = collect($request->degree)->map(function ($degreeData, $index) use ($request, $user_id) {
        $is_present = filter_var($degreeData['is_present'], FILTER_VALIDATE_BOOLEAN);

        $imageName = null;
        if ($request->hasFile("degree.$index.degree_image")) { 
            $image = $request->file("degree.$index.degree_image");
            $imageName = 'degree_images/' . Str::random(15) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $imageName);
        }

        return [
            'user_id' => $user_id,
            'degree_image' => $imageName, 
            'degree_title' => $degreeData['degree_title'],
            'institute_name' => $degreeData['institute_name'],
            'degree_startDate' => $degreeData['degree_startDate'],
            'degree_endDate' => $is_present ? '' : ($degreeData['degree_endDate'] ?? ''), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    })->toArray();

    Degree::insert($degrees);

    $experiences = collect($request->work_experiences)->map(function ($experienceData) use ($user_id) {
        $is_present = filter_var($experienceData['is_present'], FILTER_VALIDATE_BOOLEAN);

        return [
            'user_id' => $user_id,
            'company_name' => $experienceData['company_name'],
            'experience_startDate' => $experienceData['experience_startDate'],
            'experience_endDate' => $is_present ? '' : ($experienceData['experience_endDate'] ?? ''),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    })->toArray();

    Experience::insert($experiences);

    User::where('id', $user_id)->update(['mufti_status' => 1]);

    Mufti::updateOrCreate(
        ['user_id' => $user_id],
        [
            'user_id' => $user_id,
            'phone_number' => $request->phone_number,
            'fiqa' => $request->fiqa ?? '',
            'reason' => '',
            'status' => 1,
            'user_type' => $request->join_as ?? 'scholar',
        ]
    );

    if ($request->has('interest') && is_array($request->interest)) {
        $interests = collect($request->interest)->map(function ($interest) use ($user_id, $request) {
            return [
                'user_id' => $user_id,
                'interest' => $interest,
                'fiqa' => $request->fiqa ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        Interest::insert($interests);
    }

    $user = User::where('id', $user_id)->first();
    $user->interests = ($user->mufti_status == 2 || $user->mufti_status == 4)
        ? Interest::where('user_id', $user_id)->select('id', 'user_id', 'interest')->get()
        : [];

    $rejectionReason = "";
    if ($user->mufti_status == 3) {
        $mufti = Mufti::where('user_id', $user_id)->first();
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

    $userType = $request->join_as === 'lifecoach' ? 'life coach' : 'scholar’s';
    ActivityHelper::store_avtivity(
        $user->id,
        "User submitted a request to become a {$userType}.",
        'request'
    );

    return response()->json([
        'status' => true,
        'message' => 'Request Sent Successfully!',
        'data' => $userArray,
    ], 200);
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
        $user    = User::where(['id' => $request->user_id])->first();
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $search = $request->search;

        $excludedUserId = [$request->user_id];

        $muftis = User::with('interests')->where('name', 'LIKE', '%' . $search . '%')->whereIn('id', [9, 24])->whereIn('mufti_status', [2, 4])->whereNotIn('id', $excludedUserId)->get();

        if (count($muftis) <= 0) {
            return response()->json([
                "message" => "No scholar Found against this search",
                "status"  => false,
                "data"    => [],
            ], 200);
        } else {
            $response = [
                'message' => 'All scholar according to this search',
                'status'  => true,
                'data'    => $muftis,
            ];
            return response()->json($response, 200);
        }
    }

    public function update_interests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required|exists:users,id',
            'interests' => 'required|array',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user_id = $request->user_id;

        $user = User::find($user_id);

        $newInterests = $request->interests;
        $oldInterests = Interest::where('user_id', $user_id)->pluck('interest')->toArray();

        $interestsToAdd    = array_values(array_udiff($newInterests, $oldInterests, 'strcasecmp'));
        $interestsToRemove = array_values(array_udiff($oldInterests, $newInterests, 'strcasecmp'));

        collect($interestsToAdd)->each(fn($interest) => Interest::create(['user_id' => $user_id, 'interest' => $interest, 'fiqa' => $user->fiqa ?? '']));

        Interest::where('user_id', $user_id)
            ->whereIn('interest', $interestsToRemove)
            ->delete();

        $interests = Interest::where('user_id', $user_id)->select('id', 'user_id', 'interest')->get();

        $response = [
            'status'  => true,
            'message' => 'Interests updated successfully',
            'data'    => $interests,
        ];

        return response()->json($response, 200);
    }

}
