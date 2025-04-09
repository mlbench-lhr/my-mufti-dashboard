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
use Carbon\CarbonTimeZone;
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
        $name     = $folder . '/' . Str::random(15) . '.png';
        Storage::put('public/' . $name, $fileData);
        return $name;
    }

    public function add_event(AddEventRequest $request)
    {

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $data = [
            'user_id'           => $request->user_id,
            'image'             => $request->image,
            'event_title'       => $request->event_title,
            'event_category'    => $request->event_category,
            'question_category' => ["General", "Others"],
            'date'              => Carbon::parse($request->date),
            'duration'          => $request->duration,
            'location'          => $request->location,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'about'             => $request->about ?? '',
            'time_zone'         => $request->time_zone ?? 'Asia/Karachi',
            'question_end_time' => $request->question_end_time ?? '15',
        ];

        $data['image'] = $this->processImage($data['image'], 'event_images');

        $event   = Event::create($data);
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
                'user_id'  => $scholar['id'],
                'image'    => $scholar['image'],
                'name'     => $scholar['name'],
                'fiqa'     => $scholar['fiqa'],
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
                'user_id'  => 0,
                'image'    => $img,
                'name'     => $newScholar['name'],
                'fiqa'     => $newScholar['fiqa'],
                'category' => $newScholar['category'],
            ];
        })->each(function ($data) {
            EventScholar::create($data);
        });

        if ($user->mufti_status == 2) {
            $message = "Mufti " . $user->name . " added a new event.";
        } else {
            $message = $user->name . " added a new event.";
        }

        $user_id = $user->id;
        $type    = "event added";

        ActivityHelper::store_avtivity($user_id, $message, $type);

        function getCategoryCounts12($categories, $eventId)
        {
            return collect($categories)->mapWithKeys(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return [$category => $count];
            })->toArray();
        }

        $event_data = Event::where('id', $eventId)->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }])->first();

        $questionCategories            = $event_data->question_category;
        $event_data->question_category = getCategoryCounts12($questionCategories, $eventId);
        $event_data->save              = SaveEvent::where(['user_id' => $user_id, 'event_id' => $eventId])->exists();

        $response = [
            'status'  => true,
            'message' => 'Event Added Successfully!',
            'data'    => $event_data,
        ];
        return response()->json($response, 200);
    }

    public function update_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'       => 'required',
            'event_title'    => 'required',
            'event_category' => 'required',
            // 'date' => 'required',
            'duration'       => 'required',
            'location'       => 'required',
            'latitude'       => 'required',
            'longitude'      => 'required',
            'about'          => '',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $eventId = $request->event_id;

        $event = Event::with('scholars', 'hosted_by.interests')->where('id', $request->event_id)->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $allowedFields = ['user_id', 'image', 'event_title', 'event_category', 'date', 'duration', 'question_end_time', 'location', 'latitude', 'longitude', 'about'];
        if (isset($request->date)) {
            $parsedDate = Carbon::parse($request->date);
            $eventDate  = Carbon::parse($event->date);

            if ($parsedDate->toDateTimeString() !== $eventDate->toDateTimeString()) {

                $eventScholars = EventScholar::where('event_id', $event->id)
                    ->pluck('user_id')
                    ->filter(function ($value) {
                        return $value != 0;
                    })
                    ->toArray();
                $userData = User::where('id', $event->user_id)->first();
                $userName = $userData->name ?? '';

                $event_date  = $event->date;
                $change_date = $request->date;
                $oldDateTime = Carbon::parse($event_date)->format('F j, Y, g:i A');
                $newDateTime = Carbon::parse($change_date)->format('F j, Y, g:i A');

                array_walk($eventScholars, function ($value) use ($oldDateTime, $newDateTime, $userName, $eventId) {
                    $user             = User::find($value);
                    $device_id        = $user->device_id;
                    $title            = "Event Schedule Update";
                    $notiBody         = 'User ' . $userName . ' changed the event schedule from ' . $oldDateTime . ' to ' . $newDateTime . '!';
                    $body             = 'User ' . $userName . ' changed the event schedule from ' . $oldDateTime . ' to ' . $newDateTime . '!';
                    $messageType      = "Event Request Update";
                    $otherData        = "Event Request Update";
                    $notificationType = "event_schedule_update";

                    if ($device_id != "") {
                        $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType, 0, $eventId, 0);
                    }

                    $data = [
                        'user_id'        => $user->id,
                        'title'          => $title,
                        'body'           => $body,
                        'event_id'       => $eventId ?? "",
                        'question_id'    => "",
                        'appointment_id' => "",
                    ];

                    Notification::create($data);
                });
            }
            $request->merge(['date' => $parsedDate]);
        }

        $data = $request->only($allowedFields);

        $data['event_category'] = (count($request->event_category) === 0) ? $event->event_category : $request->event_category;

        $data = array_filter($data, fn($value) => $value !== null && $value !== '' && $value !== 0);

        if (! empty($data['image'])) {
            $data['image'] = $this->processImage($data['image'], 'event_images');
        }

        $event->update($data);
        $updatedEvent = Event::with(['scholars', 'hosted_by.interests', 'event_questions.user_detail', 'event_questions.likes'])->where('id', $event->id)->first();
        if (! empty($updatedEvent->question_category)) {
            $updatedEvent->question_category = array_combine($updatedEvent->question_category, array_fill(0, count($updatedEvent->question_category), 0));
        }

        return ResponseHelper::jsonResponse(true, 'Updated Event successfully!', $updatedEvent);
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

        if (! $event) {
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

        $addedScholars = collect($registerScholars)->map(function ($scholar) use ($eventId) {
            return EventScholar::create([
                'event_id' => $eventId,
                'user_id'  => $scholar['id'],
                'image'    => $scholar['image'],
                'name'     => $scholar['name'],
                'fiqa'     => $scholar['fiqa'],
                'category' => $scholar['interest'],
            ]);
        });

        $name     = $request->name;
        $fiqa     = $request->fiqa;
        $category = $request->category;
        $image    = $request->image;

        if (! empty($name)) {
            $img = ! empty($image) ? $this->processImage($image, 'event_scholar') : "";

            $manualScholar = EventScholar::create([
                'event_id' => $eventId,
                'user_id'  => 0,
                'image'    => $img,
                'name'     => $name ?? "",
                'fiqa'     => $fiqa ?? "",
                'category' => $category,
            ]);

            $addedScholars->push($manualScholar);
        }

        $allScholars = EventScholar::where('event_id', $eventId)->get();
        return ResponseHelper::jsonResponse(true, 'Added Event Scholars Successfully!', $allScholars->toArray());
    }

    public function remove_event_scholar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'   => 'required',
            'scholar_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::where('id', $request->event_id)->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $scholar = EventScholar::where('id', $request->scholar_id)->first();

        if (! $scholar) {
            return ResponseHelper::jsonResponse(false, 'Scholar Not Found');
        }

        $scholar->delete();

        return ResponseHelper::jsonResponse(true, 'Scholar deleted Successfully!');
    }

    public function event_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'user_id'  => 'required',
        ]);
        $userId = $request->user_id;

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $event = Event::with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->where('id', $request->event_id)->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $eventCategories    = $event->event_category;
        $questionCategories = $event->question_category;

        $categoryCounts = collect($eventCategories)->map(function ($value) use ($request) {
            $count = EventQuestion::where(['event_id' => $request->event_id, 'category' => $value])->count();
            return (object) [$value => $count];
        })->values()->all();

        $questionCounts = collect($questionCategories)->map(function ($value) use ($request) {
            $count = EventQuestion::where(['event_id' => $request->event_id, 'category' => $value])->count();
            return (object) [$value => $count];
        })->values()->all();

        $event->event_category    = $categoryCounts;
        $event->question_category = $questionCounts;

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $request->event_id])->exists();

        $response = [
            'status'  => true,
            'message' => 'Event detail!',
            'data'    => $event,
        ];
        return response()->json($response, 200);
    }

    public function add_question_on_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'user_id'  => 'required',
            'category' => 'required',
            'question' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $event = Event::where('id', $request->event_id)->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        // $timeZone        = $event->time_zone ?? 'Asia/Karachi';
        // $eventTimeZone   = new CarbonTimeZone($timeZone ?? 'UTC');
        // $eventTime       = Carbon::parse($event->date, $eventTimeZone)->utc();
        // $questionEndTime = $eventTime->subMinutes((int) $event->question_end_time);
        // $currentTime     = Carbon::now('UTC');
        $timeZone       = $event->time_zone ?? 'Asia/Karachi';
        $eventTimeZone  = new CarbonTimeZone($timeZone ?? 'UTC');
        $eventStartTime = Carbon::parse($event->date, $eventTimeZone)->utc();

        $eventEndTime = $eventStartTime->addHours((int) $event->duration);

        $questionEndTime = $eventEndTime->subMinutes((int) $event->question_end_time);

        $currentTime = Carbon::now('UTC');

        if ($currentTime->greaterThan($questionEndTime)) {
            return ResponseHelper::jsonResponse(false, 'You can no longer ask questions for this event');
        }

        $existingQuestion = EventQuestion::where([
            'event_id' => $request->event_id,
            'user_id'  => $request->user_id,
        ])->first();

        if ($existingQuestion) {
            return ResponseHelper::jsonResponse(false, 'You have already submitted a question');
        }

        $data = [
            'event_id' => $request->event_id,
            'user_id'  => $request->user_id,
            'question' => $request->question,
            'category' => $request->category,
        ];

        EventQuestion::create($data);
        return ResponseHelper::jsonResponse(true, 'Question Added Successfully!');
    }

    public function add_answer_on_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required',
            'question_id' => 'required',
            'answer'      => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $question = EventQuestion::where('id', $request->question_id)->first();

        if (! $question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $event = Event::where(['id' => $question->event_id, 'user_id' => $request->user_id])->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $existingAnswer = $question->answer;

        if ($existingAnswer) {
            return ResponseHelper::jsonResponse(false, 'You have already submitted an answer');
        }

        $question->update(['answer' => $request->answer]);
        return ResponseHelper::jsonResponse(true, 'Answer Added Successfully!');
    }

    public function past_upcoming_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flag'      => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $todayDate = Carbon::now($request->time_zone);
        $page      = $request->input('page', 1);
        $perPage   = 20;

        if ($request->flag == 1) {
            $pastEvents     = Event::forPage($page, $perPage)->where('date', '<', $todayDate)->where('event_status', 1)->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();
            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response       = [
                'status'              => true,
                'message'             => 'Past Events!',
                'totalPastEventPages' => $totalPastPages,
                'data'                => $pastEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::forPage($page, $perPage)->where('date', '>', $todayDate)->where('event_status', 1)->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response           = [
                'status'                  => true,
                'message'                 => 'Upcomig Events!',
                'totalUpcomingEventPages' => $totalUpcomingPages,
                'data'                    => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
    }

    public function my_past_upcoming_requested_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'flag'      => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $todayDate = Carbon::now($request->time_zone);
        $page      = $request->input('page', 1);
        $perPage   = 20;

        if ($request->flag == 1) {
            $pastEvents     = Event::forPage($page, $perPage)->where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();
            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response       = [
                'status'              => true,
                'message'             => 'Past Events!',
                'totalPastEventPages' => $totalPastPages,
                'data'                => $pastEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::forPage($page, $perPage)->where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response           = [
                'status'                  => true,
                'message'                 => 'Upcomig Events!',
                'totalUpcomingEventPages' => $totalUpcomingPages,
                'data'                    => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 3) {
            $allUserEvents    = Event::forPage($page, $perPage)->where(['event_status' => 2, 'user_id' => $request->user_id])->select('id', 'image', 'event_title', 'event_category', 'date', 'duration', 'event_status', 'location')->get();
            $totalEventsPages = ceil(Event::where(['event_status' => 2, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response         = [
                'status'              => true,
                'message'             => 'Total Events!',
                'totalPastEventPages' => $totalEventsPages,
                'data'                => $allUserEvents,
            ];
            return response()->json($response, 200);
        }
    }

    public function all_past_upcoming_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'flag'      => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $todayDate = Carbon::now($request->time_zone);
        $page      = $request->input('page', 1);
        $perPage   = 20;
        $userId    = $request->user_id;

        function getCategoryCounts($categories, $eventId)
        {
            return collect($categories)->mapWithKeys(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return [$category => $count];
            })->toArray();
        }

        if ($request->flag == 1) {
            $pastEvents = Event::where('date', '<', $todayDate)
                ->where('event_status', 1)
                ->where('user_id', '!=', $userId)
                ->orderBy('date', 'desc')
                ->forPage($page, $perPage)
                ->with('scholars', 'hosted_by.interests')
                ->with(['event_questions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();

            $pastEvents->each(function ($event) use ($request) {
                $questionCategories       = $event->question_category;
                $event->question_category = getCategoryCounts($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
            });

            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response       = [
                'status'     => true,
                'message'    => 'Past Events!',
                'totalPages' => $totalPastPages,
                'data'       => $pastEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::where('date', '>', $todayDate)
                ->where('event_status', 1)
                ->where('user_id', '!=', $userId)
                ->orderBy('date', 'desc')
                ->forPage($page, $perPage)
                ->with('scholars', 'hosted_by.interests')
                ->with(['event_questions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();

            $upcomingEvents->each(function ($event) use ($request) {
                $questionCategories = $event->question_category;

                $event->question_category = getCategoryCounts($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
            });

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where('event_status', 1)->get()->count() / $perPage);
            $response           = [
                'status'     => true,
                'message'    => 'Upcomig Events!',
                'totalPages' => $totalUpcomingPages,
                'data'       => $upcomingEvents,
            ];
            return response()->json($response, 200);
        } else {
            return ResponseHelper::jsonResponse(false, 'Invalid flag!');
        }
    }

    public function my_all_past_upcoming_requested_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'flag'      => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $todayDate = Carbon::now($request->time_zone);
        $page      = $request->input('page', 1);
        $perPage   = 20;
        $userId    = $request->user_id;

        function getCategoryCounts1($categories, $eventId)
        {
            return collect($categories)->mapWithKeys(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return [$category => $count];
            })->toArray();
        }

        if ($request->flag == 1) {
            $pastEvents = Event::where('date', '<', $todayDate)
                ->where(['event_status' => 1, 'user_id' => $request->user_id])
                ->orderBy('date', 'desc')
                ->forPage($page, $perPage)
                ->with('scholars', 'hosted_by.interests')
                ->with(['event_questions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();

            $pastEvents->each(function ($event) use ($request) {
                $questionCategories = $event->question_category;

                $event->question_category = getCategoryCounts1($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
            });

            $totalPastPages = ceil(Event::where('date', '<', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response       = [
                'status'     => true,
                'message'    => 'Past Events!',
                'totalPages' => $totalPastPages,
                'data'       => $pastEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 2) {
            $upcomingEvents = Event::where('date', '>', $todayDate)
                ->where(['event_status' => 1, 'user_id' => $request->user_id])
                ->orderBy('date', 'desc')
                ->forPage($page, $perPage)
                ->with('scholars', 'hosted_by.interests')
                ->with(['event_questions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }])
                ->get();

            $upcomingEvents->each(function ($event) use ($request) {
                $questionCategories = $event->question_category;

                $event->question_category = getCategoryCounts1($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
            });

            $totalUpcomingPages = ceil(Event::where('date', '>', $todayDate)->where(['event_status' => 1, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response           = [
                'status'     => true,
                'message'    => 'Upcomig Events!',
                'totalPages' => $totalUpcomingPages,
                'data'       => $upcomingEvents,
            ];
            return response()->json($response, 200);
        }
        if ($request->flag == 3) {
            $allUserEvents = Event::forPage($page, $perPage)->whereIn('event_status', [0, 2])->where(['user_id' => $request->user_id])->with('scholars', 'hosted_by.interests')->with(['event_questions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->orderBy('date', 'desc')->get();

            $allUserEvents->each(function ($event) use ($request) {
                $questionCategories = $event->question_category;

                $event->question_category = getCategoryCounts1($questionCategories, $event->id);

                $event->save = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
            });

            $totalEventsPages = ceil(Event::where(['event_status' => 2, 'user_id' => $request->user_id])->get()->count() / $perPage);
            $response         = [
                'status'     => true,
                'message'    => 'Total Events!',
                'totalPages' => $totalEventsPages,
                'data'       => $allUserEvents,
            ];
            return response()->json($response, 200);
        } else {
            return ResponseHelper::jsonResponse(false, 'Invalid flag!');
        }
    }

    public function sava_unsave_event(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required',
            'event_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $event = Event::where('id', $request->event_id)->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $save_event = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $request->event_id])->first();
        if ($save_event) {
            $save_event->delete();
            return ResponseHelper::jsonResponse(true, 'Event Unsave Successfully!');
        } else {
            $save = new SaveEvent;
            $save->create([
                'user_id'  => $request->user_id,
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
        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User  Not Found');
        }

        $page    = $request->input('page', 1);
        $perPage = 20;
        $search  = $request->search;

        $userId = $request->user_id;

        function getCategoryCounts2($categories, $eventId)
        {
            return collect($categories)->mapWithKeys(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return [$category => $count];
            })->toArray();
        }

        $userSaveEvents = SaveEvent::where('user_id', $request->user_id)->pluck('event_id')->toArray();

        if (! empty($search)) {
            $userSaveEvents = Event::where(function ($query) use ($search) {
                $query->where('event_title', 'LIKE', '%' . $search . '%')
                    ->orWhere('location', 'LIKE', '%' . $search . '%')
                    ->orWhere('event_category', 'LIKE', "%{$search}%");
            })->forPage($page, $perPage)->whereIn('id', $userSaveEvents)->with('scholars', 'hosted_by.interests')->get();
        } else {
            $userSaveEvents = Event::forPage($page, $perPage)->whereIn('id', $userSaveEvents)->with('scholars', 'hosted_by.interests')->get();
        }

        $userSaveEvents->transform(function ($event) use ($userId) {
            $questionCategories       = $event->question_category;
            $event->question_category = getCategoryCounts2($questionCategories, $event->id);

            $event->your_question = EventQuestion::where(['event_id' => $event->id, 'user_id' => $userId])->first();

            $event->event_questions = EventQuestion::where('event_id', $event->id)
                ->where('user_id', '!=', $userId)
                ->get();

            $event->save = SaveEvent::where(['user_id' => $userId, 'event_id' => $event->id])->exists();

            $eventArray = $event->toArray();

            $hostedBy       = ['hosted_by' => $eventArray['hosted_by']];
            $yourQuestion   = ['your_question' => $eventArray['your_question']];
            $eventQuestions = ['event_questions' => $eventArray['event_questions']];

            unset($eventArray['hosted_by'], $eventArray['your_question'], $eventArray['event_questions']);

            $reorderedArray = array_merge(
                $eventArray,
                $hostedBy,
                $yourQuestion,
                $eventQuestions
            );

            return collect($reorderedArray);
        });

        $totalPages = ceil($userSaveEvents->count() / $perPage);

        $response = [
            'status'     => true,
            'message'    => 'User  Save Events!',
            'totalPages' => $totalPages,
            'data'       => $userSaveEvents,
        ];
        return response()->json($response, 200);
    }

    public function search_event(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'flag'      => 'required',
            'time_zone' => "required",
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }
        $todayDate = Carbon::now($request->time_zone);
        $search    = $request->search;

        $userId = $request->user_id;

        function getCategoryCounts3($categories, $eventId)
        {
            return collect($categories)->mapWithKeys(function ($category) use ($eventId) {
                $count = EventQuestion::where(['event_id' => $eventId, 'category' => $category])->count();
                return [$category => $count];
            })->toArray();
        }

        $is_user_events = $request->is_user_events ?? false;

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

            $questionCategories       = $event->question_category;
            $event->question_category = getCategoryCounts3($questionCategories, $event->id);
            $event->save              = SaveEvent::where(['user_id' => $request->user_id, 'event_id' => $event->id])->exists();
        });

        if ($events->isEmpty()) {
            return response()->json([
                "message" => "No Event Found against this search",
                "status"  => false,
                "data"    => [],
            ], 200);
        }

        $response = [
            'message' => 'All Events according to this search',
            'status'  => true,
            'data'    => $events,
        ];

        return response()->json($response, 200);
    }

    public function all_questions_belongs_to_events(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'category' => 'required',
            'search'   => 'nullable|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $event = Event::where('id', $request->event_id)->first();

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $page    = $request->input('page', 1);
        $perPage = 20;

        $baseQuery = EventQuestion::with('user_detail:id,name,image')
            ->withCount('likes')
            ->where(['event_id' => $request->event_id, 'category' => $request->category]);

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $baseQuery->where('question', 'LIKE', '%' . $searchTerm . '%');
        }

        $totalPages = ceil($baseQuery->count() / $perPage);

        $eventQuestions = $baseQuery->orderBy('likes_count', 'desc')
            ->forPage($page, $perPage)
            ->get();

        if ($request->user_id) {
            $userLikedQuestionIds = EventQuestionLike::where('user_id', $request->user_id)
                ->whereIn('event_question_id', $eventQuestions->pluck('id'))
                ->pluck('event_question_id')
                ->toArray();

            $eventQuestions->transform(function ($question) use ($userLikedQuestionIds) {
                $question->is_like = in_array($question->id, $userLikedQuestionIds);
                return $question;
            });
        }

        $response = [
            'status'     => true,
            'message'    => 'All Questions according to this category',
            'totalPages' => $totalPages,
            'data'       => $eventQuestions,
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

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }

        $eventCategories = $event->event_category;
        $categoryCounts  = collect($eventCategories)->mapWithKeys(function ($value) use ($request) {
            $count = EventQuestion::where(['event_id' => $request->event_id, 'category' => $value])->count();
            return [$value => $count];
        })->all();

        $response = [
            'status'  => true,
            'message' => 'All Questions according to this category',
            'data'    => $categoryCounts,
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

        if (! $event) {
            return ResponseHelper::jsonResponse(false, 'Event Not Found');
        }
        $event->delete();

        return ResponseHelper::jsonResponse(true, 'Event deleted Successfully!');
    }

    public function like_dislike_event_question(EventQuestionLikeDislike $request)
    {
        $user = User::find($request->user_id);

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $question = EventQuestion::find($request->event_question_id);

        if (! $question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $check = EventQuestionLike::where([
            'user_id'           => $request->user_id,
            'event_question_id' => $request->event_question_id,
        ])->first();
        if ($check) {
            $check->delete();
            return ResponseHelper::jsonResponse(true, 'Unlike Successfully');
        }

        $data = [
            'user_id'           => $request->user_id,
            'event_question_id' => $request->event_question_id,
        ];
        EventQuestionLike::create($data);
        return ResponseHelper::jsonResponse(true, 'Like Successfully');
    }
}
