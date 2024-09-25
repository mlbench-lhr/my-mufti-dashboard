<?php

namespace App\Http\Controllers\Event;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddEventRequest;
use App\Http\Requests\EventQuestionLikeDislike;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventQuestionLike;
use App\Models\EventScholar;
use App\Models\Notification;
use App\Models\SaveEvent;
use App\Models\User;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    private function processImage($base64File, $folder)
    {
        $fileData = base64_decode($base64File);
        $name = $folder . '/' . Str::random(15) . '.png';
        Storage::put('public/' . $name, $fileData);
        return $name;
    }

    public function add_event(AddEventRequest $request)
    {

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $data = [
            'user_id' => $request->user_id,
            'image' => $request->image,
            'event_title' => $request->event_title,
            'event_category' => $request->event_category,
            'question_category' => ["General", "Others"],
            'date' => Carbon::parse($request->date),
            'duration' => $request->duration,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'about' => $request->about,
        ];

        $data['image'] = $this->processImage($data['image'], 'event_images');

        $event = Event::create($data);
        $eventId = $event->id;

        $registerScholars = $request->register_scholar;
        $registerScholars = User::with('interests')->whereIN('id', $registerScholars)->select('id', 'name', 'fiqa', 'image')->get();

        $registerScholars->map(function ($user) {
            $user->interest = $user->interests->pluck('interest')->toArray();
            unset($user->interests);
        });

        collect($registerScholars)->map(function ($scholar) use ($eventId) {
            return [
                'event_id' => $eventId,
                'user_id' => $scholar['id'],
                'image' => $scholar['image'],
                'name' => $scholar['name'],
                'fiqa' => $scholar['fiqa'],
                'category' => $scholar['interest'],
            ];
        })->each(function ($data) {
            EventScholar::create($data);
        });

        $newScholars = $request->new_scholar;
        collect($newScholars)->map(function ($newScholar) use ($eventId) {
            if ($newScholar['image'] != "") {
                $img = $this->processImage($newScholar['image'], 'event_scholar');
            } else {
                $img = "";
            }
            return [
                'event_id' => $eventId,
                'user_id' => 0,
                'image' => $img,
                'name' => $newScholar['name'],
                'fiqa' => $newScholar['fiqa'],
                'category' => $newScholar['category'],
            ];
        })->each(function ($data) {
            EventScholar::create($data);
        });

        $user_id = $user->id;
        $message = $user->name . " added a new event.";
        $type = "event added";

        ActivityHelper::store_avtivity($user_id, $message, $type);

        return ResponseHelper::jsonResponse(true, 'Event Added Successfully!');
    }

    public function update_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'event_title' => 'required',
            'event_category' => 'required',
            // 'date' => 'required',
            'duration' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'about' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::with('scholars', 'hosted_by.interests')->where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $allowedFields = ['user_id', 'image', 'event_title', 'event_category', 'date', 'duration', 'location', 'latitude', 'longitude', 'about'];
        if (isset($request->date)) {
            $parsedDate = Carbon::parse($request->date);
            $eventDate = Carbon::parse($event->date);

            if ($parsedDate->toDateTimeString() !== $eventDate->toDateTimeString()) {

                $eventScholars = EventScholar::where('event_id', $event->id)
                    ->pluck('user_id')
                    ->filter(function ($value) {
                        return $value != 0;
                    })
                    ->toArray();
                $userData = User::where('id', $event->user_id)->first();
                $userName = $userData->name ?? '';

                $event_date = $event->date;
                $change_date = $request->date;
                $oldDateTime = Carbon::parse($event_date)->format('F j, Y, g:i A');
                $newDateTime = Carbon::parse($change_date)->format('F j, Y, g:i A');

                array_walk($eventScholars, function ($value) use ($oldDateTime, $newDateTime, $userName) {
                    $user = User::find($value);
                    $device_id = $user->device_id;
                    $title = "Event Update";
                    $notiBody = 'User ' . $userName . ' changed the event schedule from ' . $oldDateTime . ' to ' . $newDateTime . '.';
                    $body = 'User ' . $userName . ' changed the event schedule from ' . $oldDateTime . ' to ' . $newDateTime . '.';
                    $messageType = "Event Update";
                    $otherData = "Event Update";
                    $notificationType = "0";

                    if ($device_id != "") {
                        $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
                    }

                    // $this->send_notification($device_id, $title, $notiBody, $messageType);

                    $data = [
                        'user_id' => $user->id,
                        'title' => $title,
                        'body' => $body,
                    ];

                    Notification::create($data);
                });
            }
            $request->merge(['date' => $parsedDate]);
        }

        $data = $request->only($allowedFields);

        $data['event_category'] = (count($request->event_category) === 0) ? $event->event_category : $request->event_category;

        $data = array_filter($data, fn($value) => $value !== null && $value !== '' && $value !== 0);

        if (!empty($data['image'])) {
            $data['image'] = $this->processImage($data['image'], 'event_images');
        }

        $event->update($data);

        return ResponseHelper::jsonResponse(true, 'Updated Event successfully!');
    }

    public function add_event_scholars(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::with('scholars', 'hosted_by.interests')->where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $eventId = $request->event_id;
        if (count($request->register_scholar) > 0) {
            EventScholar::where(['event_id' => $eventId])->where('user_id', '!=', 0)->delete();
        }

        $registerScholars = $request->register_scholar;

        $registerScholars = User::with('interests')->whereIN('id', $registerScholars)->select('id', 'name', 'fiqa', 'image')->get();

        $registerScholars->map(function ($user) {
            $user->interest = $user->interests->pluck('interest')->toArray();
            unset($user->interests);
        });

        collect($registerScholars)->map(function ($scholar) use ($eventId) {
            return [
                'event_id' => $eventId,
                'user_id' => $scholar['id'],
                'image' => $scholar['image'],
                'name' => $scholar['name'],
                'fiqa' => $scholar['fiqa'],
                'category' => $scholar['interest'],
            ];
        })->each(function ($data) {
            EventScholar::create($data);
        });

        $name = $request->name;
        $fiqa = $request->fiqa;
        $category = $request->category;
        $image = $request->image;
        if ($request->image != "") {
            $img = $this->processImage($request->image, 'event_scholar');
        } else {
            $img = "";
        }
        if ($name != "") {
            $data1 = [
                'event_id' => $eventId,
                'user_id' => 0,
                'image' => $img,
                'name' => $name ?? "",
                'fiqa' => $fiqa ?? "",
                'category' => $category,
            ];

            EventScholar::create($data1);
        }

        return ResponseHelper::jsonResponse(true, 'Added Event Scholars Successfully!');
    }

    public function remove_event_scholar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'scholar_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $scholar = EventScholar::where('id', $request->scholar_id)->first();

        if (!$scholar) {
            return ResponseHelper::jsonResponse(false, 'Scholar Not Found');
        }

        $scholar->delete();

        return ResponseHelper::jsonResponse(true, 'Scholar deleted Successfully!');

    }

    public function event_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'user_id' => 'required',
        ]);
        $userId = $request->user_id;

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $event = Event::with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $eventCategories = $event->event_category;
        $questionCategories = $event->question_category;

        $categoryCounts = collect($eventCategories)->map(function ($value) use ($request) {
            $count = EventQuestion::where(['event_id' => $request->event_id, 'category' => $value])->count();
            return (object) [$value => $count];
        })->values()->all();

        $questionCounts = collect($questionCategories)->map(function ($value) use ($request) {
            $count = EventQuestion::where(['event_id' => $request->event_id, 'category' => $value])->count();
            return (object) [$value => $count];
        })->values()->all();

        $event->event_category = $categoryCounts;
        $event->question_category = $questionCounts;

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $request->event_id])->exists();

        $response = [
            'status' => true,
            'message' => 'Event detail!',
            'data' => $event,
        ];
        return response()->json($response, 200);
    }

    public function add_question_on_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'user_id' => 'required',
            'category' => 'required',
            'question' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $event = Event::where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $data = [
            'event_id' => $request->event_id,
            'user_id' => $request->user_id,
            'question' => $request->question,
            'category' => $request->category,
        ];

        EventQuestion::create($data);
        return ResponseHelper::jsonResponse(true, 'Question Added Successfully!');

    }

    public function add_answer_on_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'question_id' => 'required',
            'answer' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $question = EventQuestion::where('id', $request->question_id)->first();

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $event = Event::where(['id' => $question->event_id, 'user_id' => $request->user_id])->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $question->update(['answer' => $request->answer]);
        return ResponseHelper::jsonResponse(true, 'Answer Added Successfully!');

    }

    public function past_upcoming_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flag' => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $todayDate = Carbon::now($request->time_zone);
        $page = $request->input('page', 1);
        $perPage = 20;

        if ($request->flag == 1) {
            $pastEvents = Event::forPage($page, $perPage)->where('date', '<', $todayDate)->where('event_status', 1)->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();
            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Past Events!',
                'totalPastEventPages' => $totalPastPages,
                'data' => $pastEvents,
            ];
            return response()->json($response, 200);

        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::forPage($page, $perPage)->where('date', '>', $todayDate)->where('event_status', 1)->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Upcomig Events!',
                'totalUpcomingEventPages' => $totalUpcomingPages,
                'data' => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
    }

    public function my_past_upcoming_requested_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'flag' => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $todayDate = Carbon::now($request->time_zone);
        $page = $request->input('page', 1);
        $perPage = 20;

        if ($request->flag == 1) {
            $pastEvents = Event::forPage($page, $perPage)->where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();
            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Past Events!',
                'totalPastEventPages' => $totalPastPages,
                'data' => $pastEvents,
            ];
            return response()->json($response, 200);

        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::forPage($page, $perPage)->where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Upcomig Events!',
                'totalUpcomingEventPages' => $totalUpcomingPages,
                'data' => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 3) {
            $allUserEvents = Event::forPage($page, $perPage)->where(['event_status' => 2, 'user_id' => $request->user_id])->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();
            $totalEventsPages = ceil(Event::where(['event_status' => 2, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Total Events!',
                'totalPastEventPages' => $totalEventsPages,
                'data' => $allUserEvents,
            ];
            return response()->json($response, 200);

        }
    }

    public function all_past_upcoming_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'flag' => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $todayDate = Carbon::now($request->time_zone);
        $page = $request->input('page', 1);
        $perPage = 20;
        $userId = $request->user_id;

        function getCategoryCounts($categories, $eventId)
        {
            return collect($categories)->map(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return (object) [$category => $count];
            })->values()->all();
        }

        if ($request->flag == 1) {

            $pastEvents = Event::forPage($page, $perPage)->where('date', '<', $todayDate)->where('event_status', 1)->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $pastEvents->each(function ($event) use ($request) {
                // $eventCategories = $event->event_category;
                // $event->event_category = getCategoryCounts($eventCategories, $event->id);

                $questionCategories = $event->question_category;
                $event->question_category = getCategoryCounts($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();

            });

            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Past Events!',
                'totalPastEventPages' => $totalPastPages,
                'data' => $pastEvents,
            ];
            return response()->json($response, 200);

        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::forPage($page, $perPage)->where('date', '>', $todayDate)->where('event_status', 1)->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $upcomingEvents->each(function ($event) use ($request) {
                // $eventCategories = $event->event_category;
                $questionCategories = $event->question_category;

                // $event->event_category = getCategoryCounts($eventCategories, $event->id);
                $event->question_category = getCategoryCounts($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();

            });

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Upcomig Events!',
                'totalUpcomingEventPages' => $totalUpcomingPages,
                'data' => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
    }

    public function my_all_past_upcoming_requested_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'flag' => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $todayDate = Carbon::now($request->time_zone);
        $page = $request->input('page', 1);
        $perPage = 20;
        $userId = $request->user_id;

        function getCategoryCounts1($categories, $eventId)
        {
            return collect($categories)->map(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return (object) [$category => $count];
            })->values()->all();
        }

        if ($request->flag == 1) {
            $pastEvents = Event::forPage($page, $perPage)->where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $pastEvents->each(function ($event) use ($request) {
                // $eventCategories = $event->event_category;
                $questionCategories = $event->question_category;

                // $event->event_category = getCategoryCounts1($eventCategories, $event->id);
                $event->question_category = getCategoryCounts1($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();

            });

            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Past Events!',
                'totalPastEventPages' => $totalPastPages,
                'data' => $pastEvents,
            ];
            return response()->json($response, 200);

        }
        if ($request->flag == 2) {

            $upcomingEvents = Event::forPage($page, $perPage)->where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $upcomingEvents->each(function ($event) use ($request) {
                // $eventCategories = $event->event_category;
                $questionCategories = $event->question_category;

                // $event->event_category = getCategoryCounts1($eventCategories, $event->id);
                $event->question_category = getCategoryCounts1($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();

            });

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Upcomig Events!',
                'totalUpcomingEventPages' => $totalUpcomingPages,
                'data' => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 3) {
            $allUserEvents = Event::forPage($page, $perPage)->where(['event_status' => 2, 'user_id' => $request->user_id])->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();

            $allUserEvents->each(function ($event) use ($request) {
                // $eventCategories = $event->event_category;
                $questionCategories = $event->question_category;

                // $event->event_category = getCategoryCounts1($eventCategories, $event->id);
                $event->question_category = getCategoryCounts1($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();

            });

            $totalEventsPages = ceil(Event::where(['event_status' => 2, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response = [
                'status' => true,
                'message' => 'Total Events!',
                'totalPastEventPages' => $totalEventsPages,
                'data' => $allUserEvents,
            ];
            return response()->json($response, 200);

        }
    }

    public function sava_unsave_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'event_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $event = Event::where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $save_event = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $request->event_id])->first();
        if ($save_event) {
            $save_event->delete();
            return ResponseHelper::jsonResponse(true, 'Event Unsave Successfully!');

        } else {
            $save = new SaveEvent;
            $save->create([
                'user_id' => $request->user_id,
                'event_id' => $request->event_id,
            ]);

            return ResponseHelper::jsonResponse(true, 'Event Save Successfully!');

        }
    }

    public function user_save_events(Request $request)
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
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $page = $request->input('page', 1);
        $perPage = 20;
        $userId = $request->user_id;

        function getCategoryCounts2($categories, $eventId)
        {
            return collect($categories)->map(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return (object) [$category => $count];
            })->values()->all();
        }

        $userSaveEvents = SaveEvent::where('user_id', $request->user_id)->pluck('event_id')->toArray();

        // $userSaveEvents = Event::forPage($page, $perPage)->whereIn('id', $userSaveEvents)->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();

        $userSaveEvents = Event::forPage($page, $perPage)->whereIn('id', $userSaveEvents)->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();

        $userSaveEvents->each(function ($event) use ($request) {
            // $eventCategories = $event->event_category;
            // $event->event_category = getCategoryCounts2($eventCategories, $event->id);

            $questionCategories = $event->question_category;
            $event->question_category = getCategoryCounts2($questionCategories, $event->id);

            $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
        });

        $totalPages = ceil(SaveEvent::forPage($page, $perPage)->where('user_id', $request->user_id)->get()->count() / $perPage);
        $response = [
            'status' => true,
            'message' => 'User Save Events!',
            'totalPages' => $totalPages,
            'data' => $userSaveEvents,
        ];
        return response()->json($response, 200);

    }

    public function search_event(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'flag' => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $todayDate = Carbon::now($request->time_zone);
        $search = $request->search;

        $userId = $request->user_id;

        function getCategoryCounts3($categories, $eventId)
        {
            return collect($categories)->map(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return (object) [$category => $count];
            })->values()->all();
        }

        $is_user_events = $request->is_user_events ?? false;

        // $query = Event::where(function ($query) use ($search) {
        //     $query->where('event_title', 'LIKE', '%' . $search . '%')
        //         ->orWhere('location', 'LIKE', '%' . $search . '%')
        //         ->orWhere('event_category', 'LIKE', "%{$search}%");
        // })
        //     ->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location');

        $query = Event::where(function ($query) use ($search) {
            $query->where('event_title', 'LIKE', '%' . $search . '%')
                ->orWhere('location', 'LIKE', '%' . $search . '%')
                ->orWhere('event_category', 'LIKE', "%{$search}%");
        })->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }]);

        if ($is_user_events == true) {
            $query->where('user_id', $request->user_id);

            if ($request->flag == 3) {

            } else {

                if ($request->flag == 1) {
                    $query->where('date', '<', $todayDate);
                } elseif ($request->flag == 2) {
                    $query->where('date', '>', $todayDate);
                }

                $query->where('event_status', 1);
            }
        } else {
            if ($request->flag == 1) {
                $query->where('date', '<', $todayDate);
            } elseif ($request->flag == 2) {
                $query->where('date', '>', $todayDate);
            }

            $query->where('event_status', 1);
        }

        $events = $query->get();

        $events->each(function ($event) use ($request) {
            // $eventCategories = $event->event_category;
            // $event->event_category = getCategoryCounts($eventCategories, $event->id);

            $questionCategories = $event->question_category;
            $event->question_category = getCategoryCounts3($questionCategories, $event->id);
            $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
        });

        if ($events->isEmpty()) {
            return response()->json([
                "message" => "No Event Found against this search",
                "status" => false,
                "data" => [],
            ], 200);
        }

        $response = [
            'message' => 'All Events according to this search',
            'status' => true,
            'data' => $events,
        ];

        return response()->json($response, 200);

    }

    public function all_questions_belongs_to_events(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'category' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $page = $request->input('page', 1);
        $perPage = 20;
        $totalPages = ceil(EventQuestion::where(['event_id' => $request->event_id, 'category' => $request->category])->get()->count() / $perPage);

        // $eventQuestions = EventQuestion::with('user_detail:id,name,image')->forPage($page, $perPage)->where(['event_id' => $request->event_id, 'category' => $request->category])->get();

        $eventQuestions = EventQuestion::with('user_detail:id,name,image')
            ->withCount('likes')
            ->where(['event_id' => $request->event_id, 'category' => $request->category])
            ->orderBy('likes_count', 'desc')
            ->forPage($page, $perPage)
            ->get();

        $response = [
            'status' => true,
            'message' => 'All Questions according to this category',
            'totalPages' => $totalPages,
            'data' => $eventQuestions,
        ];

        return response()->json($response, 200);

    }

    public function all_category_belongs_to_events(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $eventCategories = $event->event_category;
        $categoryCounts = collect($eventCategories)->mapWithKeys(function ($value) use ($request) {
            $count = EventQuestion::where(['event_id' => $request->event_id, 'category' => $value])->count();
            return [$value => $count];
        })->all();

        $response = [
            'status' => true,
            'message' => 'All Questions according to this category',
            'data' => $categoryCounts,
        ];

        return response()->json($response, 200);

    }

    public function delete_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::where('id', $request->event_id)->first();

        if (!$event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $event->delete();

        return ResponseHelper::jsonResponse(true, 'Event deleted Successfully!');

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

    public function like_dislike_event_question(EventQuestionLikeDislike $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $question = EventQuestion::find($request->event_question_id);

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $check = EventQuestionLike::where([
            'user_id' => $request->user_id,
            'event_question_id' => $request->event_question_id,
        ])->first();
        if ($check) {
            $check->delete();
            return ResponseHelper::jsonResponse(true, 'Unlike Successfully');
        }

        $data = [
            'user_id' => $request->user_id,
            'event_question_id' => $request->event_question_id,
        ];
        EventQuestionLike::create($data);
        return ResponseHelper::jsonResponse(true, 'Like Successfully');

    }

}
