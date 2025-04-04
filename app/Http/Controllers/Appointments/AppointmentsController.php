<?php
namespace App\Http\Controllers\Appointments;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddSlots;
use App\Http\Requests\UserId;
use App\Models\MuftiAppointment;
use App\Models\Notification;
use App\Models\User;
use App\Models\WorkingDay;
use App\Models\WorkingSlot;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppointmentsController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function add_slots(AddSlots $request)
    {
        $user_id = $request->user_id;
    $user    = User::where('id', $user_id)->first();

    if (! $user) {
        return ResponseHelper::jsonResponse(false, 'User Not Found');
    }

    $day_name   = $request->day_name;
    $workingDay = WorkingDay::where(['user_id' => $user_id, 'day_name' => $day_name])->first();

    if (! $workingDay) {
        return ResponseHelper::jsonResponse(false, 'Day Not Found');
    }

    if ($request->is_available) {
        $workingDay->update(['is_available' => true]);

        $newSlots = collect($request->slots)->map(function ($slot) {
            return [
                'start_time' => date('H:i', strtotime($slot['start_time'])),
                'end_time'   => date('H:i', strtotime($slot['end_time'])),
            ];
        });

        $existingSlots = WorkingSlot::where('working_day_id', $workingDay->id)->get()->mapWithKeys(function ($slot) {
            return [
                $slot->id => [
                    'start_time' => date('H:i', strtotime($slot->start_time)),
                    'end_time'   => date('H:i', strtotime($slot->end_time)),
                    'status'     => $slot->status,
                ],
            ];
        });

        $slotsToUpdate = collect($existingSlots)->filter(fn($slot) =>
            $slot['status'] !== 1 &&
            $newSlots->contains(fn($newSlot) =>
                $newSlot['start_time'] === $slot['start_time'] && $newSlot['end_time'] === $slot['end_time']
            )
        );

        if ($slotsToUpdate->isNotEmpty()) {
            WorkingSlot::whereIn('id', $slotsToUpdate->keys())->update(['status' => 1]);
        }

        $slotsToInsert = $newSlots->filter(fn($newSlot) =>
            ! collect($existingSlots)->contains(fn($slot) =>
                $slot['start_time'] === $newSlot['start_time'] && $slot['end_time'] === $newSlot['end_time']
            )
        )->map(fn($slot) => [
            'working_day_id' => $workingDay->id,
            'start_time'     => $slot['start_time'],
            'end_time'       => $slot['end_time'],
            'status'         => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ])->toArray();

        if (! empty($slotsToInsert)) {
            WorkingSlot::insert($slotsToInsert);
        }

        // Restore previously closed slots
        WorkingSlot::where('working_day_id', $workingDay->id)
            ->where('status', 2)
            ->update(['status' => 1]);
    } else {
        $workingDay->update(['is_available' => false]);

        // $bookedFutureSlotIds = DB::table('mufti_appointments')
        //     ->whereRaw("STR_TO_DATE(date, '%Y-%m-%d') >= ?", [Carbon::today()->format('Y-m-d')])
        //     ->pluck('selected_slot')
        //     ->toArray();

        //     $bookedPastSlotIds = DB::table('mufti_appointments')
        //     ->whereRaw("STR_TO_DATE(date, '%Y-%m-%d') < ?", [Carbon::today()->format('Y-m-d')])
        //     ->pluck('selected_slot')
        //     ->toArray();     


        //     WorkingSlot::where('working_day_id', $workingDay->id)
        //     ->whereIn('id', $bookedFutureSlotIds)
        //     ->where('status', 1)
        //     ->update(['status' => 3]);



        //     WorkingSlot::where('working_day_id', $workingDay->id)
        //     ->whereIn('id', $bookedPastSlotIds)
        //     ->where('status', 1)
        //     ->update(['status' => 2]);


        // $mergedSlotIds = array_merge(array_unique($bookedFutureSlotIds), array_unique($bookedPastSlotIds));
        
        // WorkingSlot::where('working_day_id', $workingDay->id)
        //     ->whereNotIn('id', $mergedSlotIds)->where('status', 1)
        //     ->delete();
    }

    return ResponseHelper::jsonResponse(true, 'Slots updated successfully');
    }
        // $user_id = $request->user_id;
        // $user    = User::where('id', $user_id)->first();

        // if (! $user) {
        //     return ResponseHelper::jsonResponse(false, 'User Not Found');
        // }

        // $day_name   = $request->day_name;
        // $workingDay = WorkingDay::where(['user_id' => $user_id, 'day_name' => $day_name])->first();

        // if (! $workingDay) {
        //     return ResponseHelper::jsonResponse(false, 'Day Not Found');
        // }

        // if ($request->is_available) {
        //     $workingDay->update(['is_available' => true]);

        //     $newSlots = collect($request->slots)->map(function ($slot) {
        //         return [
        //             'start_time' => date('H:i', strtotime($slot['start_time'])),
        //             'end_time'   => date('H:i', strtotime($slot['end_time'])),
        //         ];
        //     });

        //     $existingSlots = WorkingSlot::where('working_day_id', $workingDay->id)->get()->map(function ($slot) {
        //         return [
        //             'id'         => $slot->id,
        //             'start_time' => date('H:i', strtotime($slot->start_time)),
        //             'end_time'   => date('H:i', strtotime($slot->end_time)),
        //         ];
        //     });

        //     $slotsToDelete = $existingSlots->filter(fn($slot) =>
        //         ! $newSlots->contains(fn($newSlot) =>
        //             $newSlot['start_time'] === $slot['start_time'] && $newSlot['end_time'] === $slot['end_time']
        //         )
        //     );

        //     if ($slotsToDelete->isNotEmpty()) {
        //         WorkingSlot::whereIn('id', $slotsToDelete->pluck('id'))->delete();
        //     }

        //     $slotsToInsert = $newSlots->filter(fn($newSlot) =>
        //         ! $existingSlots->contains(fn($slot) =>
        //             $slot['start_time'] === $newSlot['start_time'] && $slot['end_time'] === $newSlot['end_time']
        //         )
        //     )->map(fn($slot) => [
        //         'working_day_id' => $workingDay->id,
        //         'start_time'     => $slot['start_time'],
        //         'end_time'       => $slot['end_time'],
        //         'created_at'     => now(),
        //         'updated_at'     => now(),
        //     ])->toArray();

        //     if (! empty($slotsToInsert)) {
        //         WorkingSlot::insert($slotsToInsert);
        //     }

        // }

        // if ($request->is_available) {
        //     $workingDay->update(['is_available' => true]);

        //     $newSlots = collect($request->slots)->map(function ($slot) {
        //         return [
        //             'start_time' => date('H:i', strtotime($slot['start_time'])),
        //             'end_time'   => date('H:i', strtotime($slot['end_time'])),
        //         ];
        //     });

        //     $existingSlots = WorkingSlot::where('working_day_id', $workingDay->id)->get()->mapWithKeys(function ($slot) {
        //         return [
        //             $slot->id => [
        //                 'start_time' => date('H:i', strtotime($slot->start_time)),
        //                 'end_time'   => date('H:i', strtotime($slot->end_time)),
        //                 'status'     => $slot->status,
        //             ],
        //         ];
        //     });

        //     $slotsToDelete = collect($existingSlots)->filter(fn($slot) =>
        //         ! $newSlots->contains(fn($newSlot) =>
        //             $newSlot['start_time'] === $slot['start_time'] && $newSlot['end_time'] === $slot['end_time']
        //         )
        //     );

            // if ($slotsToDelete->isNotEmpty()) {
            //     WorkingSlot::whereIn('id', $slotsToDelete->keys())->delete();
            // }

        //     $slotsToUpdate = collect($existingSlots)->filter(fn($slot) =>
        //         $slot['status'] !== 1 &&
        //         $newSlots->contains(fn($newSlot) =>
        //             $newSlot['start_time'] === $slot['start_time'] && $newSlot['end_time'] === $slot['end_time']
        //         )
        //     );

        //     if ($slotsToUpdate->isNotEmpty()) {
        //         WorkingSlot::whereIn('id', $slotsToUpdate->keys())->update(['status' => 1]);
        //     }

        //     $slotsToInsert = $newSlots->filter(fn($newSlot) =>
        //         ! collect($existingSlots)->contains(fn($slot) =>
        //             $slot['start_time'] === $newSlot['start_time'] && $slot['end_time'] === $newSlot['end_time']
        //         )
        //     )->map(fn($slot) => [
        //         'working_day_id' => $workingDay->id,
        //         'start_time'     => $slot['start_time'],
        //         'end_time'       => $slot['end_time'],
        //         'status'         => 1,
        //         'created_at'     => now(),
        //         'updated_at'     => now(),
        //     ])->toArray();

        //     if (! empty($slotsToInsert)) {
        //         WorkingSlot::insert($slotsToInsert);
        //     }

        // } else {
        //     $workingDay->update(['is_available' => false]);

        //     WorkingSlot::where('working_day_id', $workingDay->id)
        //         ->where('status', 1)
        //         ->whereIn('id', function ($query) {
        //             $query->select('selected_slot')
        //                 ->from('mufti_appointments')
        //                 ->whereRaw("STR_TO_DATE(date, '%Y-%m-%d') >= ?", [Carbon::today()->format('Y-m-d')]);
        //         })
        //         ->update(['status' => 3]);

        //     WorkingSlot::where('working_day_id', $workingDay->id)
        //         ->where('status', 1)
        //         ->update(['status' => 2]);
        // }

        // return ResponseHelper::jsonResponse(true, 'Slots updated successfully');

    public function remove_slot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slot_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $slot_id = $request->slot_id;
        $slot    = WorkingSlot::find($slot_id);

        if (! $slot) {
            return ResponseHelper::jsonResponse(false, 'Slot Not Found');
        }

        $appointments = MuftiAppointment::where('selected_slot', $slot_id)->get();

        if ($appointments->isNotEmpty()) {
            $hasUpcomingOrTodayAppointments = $appointments->filter(fn($appointment) =>
                Carbon::parse($appointment->date)->isToday() || Carbon::parse($appointment->date)->isFuture()
            );

            if ($hasUpcomingOrTodayAppointments->isNotEmpty()) {

                $slot->update(['status' => 3]);

                return ResponseHelper::jsonResponse(false,
                    'You can’t delete this time slot as there is an appointment today or in the future. ' .
                    'After the appointment, it will be automatically deleted.'
                );
            }

        }

        $slot->update(['status' => 2]);

        return ResponseHelper::jsonResponse(true, 'Slot deleted successfully.');
    }

    public function get_slots(UserId $request)
    {
        $user_id = $request->user_id;

        WorkingSlot::where('status', 3)
            ->whereDoesntHave('appointments', function ($query) {
                $query->whereRaw("STR_TO_DATE(date, '%Y-%m-%d') >= ?", [Carbon::today()->format('Y-m-d')]);
            })
            ->update(['status' => 2]);


        $workingDays = WorkingDay::where('user_id', $user_id)
            ->with(['slots' => function ($query) {
                $query->whereIn('status', [1,3]);
            }])
            ->get()
            ->map(function ($day) {
                if ($day->slots->isEmpty()) {
                    $day->is_available = 2; 
                }
                return $day;
            })
            ->sortBy(function ($day) {
                return array_search($day->day_name, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            })->values();


        if ($workingDays->isEmpty()) {
            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            $workingDaysData = array_map(fn($day) => [
                'user_id'      => $user_id,
                'day_name'     => $day,
                'is_available' => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ], $daysOfWeek);

            WorkingDay::insert($workingDaysData);

            $workingDays = WorkingDay::where('user_id', $user_id)->with('slots')->get();
        }       


        return ResponseHelper::jsonResponseWithData(true, "Added Successfully", $workingDays);
    }

    public function get_mufti_slots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date'    => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user_id = $request->user_id;
        $date    = $request->date;

        $dayName = Carbon::parse($date)->format('l');

        $workingDay = WorkingDay::where([
            'user_id'      => $user_id,
            'day_name'     => $dayName,
            'is_available' => true,
        ])->first();

        if (! $workingDay) {
            return ResponseHelper::jsonResponse(false, 'On this date, the mufti is not available');
        }

        $currentDate = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->format('H:i:s');

        $availableSlots = WorkingSlot::where([
           'working_day_id' => $workingDay->id,
           'status'         => 1,
        ])
        ->when($date === $currentDate, function ($query) use ($currentTime) {
          $query->where('start_time', '>=', $currentTime);
        })->get();

        $bookedSlotIds = MuftiAppointment::where([
            'mufti_id' => $user_id,
            'date'     => $date,
        ])->pluck('selected_slot')->toArray();

        $slotsWithBookingStatus = $availableSlots->map(function ($slot) use ($bookedSlotIds) {
            return [
                'id'         => $slot->id,
                'start_time' => $slot->start_time,
                'end_time'   => $slot->end_time,
                'is_booked'  => in_array($slot->id, $bookedSlotIds) ? 2 : 1,
            ];
        });

        return ResponseHelper::jsonResponseWithData(true, "All Slots", $slotsWithBookingStatus);
    }

    public function book_an_appointment(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            'user_id'          => 'required',
            'mufti_id'         => 'required',
            'contact_number'   => 'required',
            'email'            => 'required',
            'category'         => 'required',
            'description'      => 'required',
            'selected_date'    => 'required',
            'selected_slot'    => 'required',
            'consultation_fee' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (! $user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $muftiTypes = [
            9  => ['status' => 2, 'type' => 'scholar', 'notFoundMessage' => 'Mufti Not Found'],
            24 => ['status' => 4, 'type' => 'lifecoach', 'notFoundMessage' => 'Life Coach Not Found'],
        ];

        if (isset($muftiTypes[$request->mufti_id])) {
            $typeDetails = $muftiTypes[$request->mufti_id];
            $mufti       = User::where(['id' => $request->mufti_id, 'mufti_status' => $typeDetails['status']])->first();
            if (! $mufti) {
                return ResponseHelper::jsonResponse(false, $typeDetails['notFoundMessage']);
            }
        } else {
            return ResponseHelper::jsonResponse(false, 'Invalid Mufti ID');
        }

        $checkSlot = MuftiAppointment::where([
            'user_id'       => $request->user_id,
            'mufti_id'      => $request->mufti_id,
            'date'          => $request->selected_date,
            'selected_slot' => $request->selected_slot,
        ])->exists();

        if ($checkSlot) {
            return ResponseHelper::jsonResponse(false, 'Someone already booked this slot.');
        }

        $data = [
            'user_id'          => $request->user_id,
            'mufti_id'         => $request->mufti_id,
            'category'         => $request->category,
            'description'      => $request->description,
            'contact_number'   => $request->contact_number ?? "",
            'email'            => $request->email ?? "",
            'date'             => $request->selected_date,
            'duration'         => $request->duration ?? "",
            'selected_slot'    => $request->selected_slot,
            'payment_id'       => $request->payment_id ?? "",
            'payment_method'   => $request->payment_method ?? "",
            'consultation_fee' => $request->consultation_fee,
            'user_type'        => $typeDetails['type'],
        ];

        $appointment = MuftiAppointment::create($data);

        $device_id        = $mufti->device_id;
        $title            = "New Appointment Request Received";
        $notiBody         = 'You have received a new appointment request from ' . $user->name . '.';
        $body             = 'You have received a new appointment request from ' . $user->name . '.';
        $messageType      = "New Appointment Request Received";
        $otherData        = "New Appointment Request Received";
        $notificationType = "request_new_appointment";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $data = [
            'user_id' => $mufti->id,
            'title'   => $title,
            'body'    => $body,
            'question_id'=>"",
            'event_id'=>"",
        ];
        Notification::create($data);

        $user_id = $user->id;
        $message = "A new appointment booked by " . $user->name;
        $type    = "booked appointment";
        ActivityHelper::store_avtivity($user_id, $message, $type);
        $appointmentDetails = MuftiAppointment::with(['user_detail', 'mufti_detail.interests', 'book_slot'])->find($appointment->id);

        return ResponseHelper::jsonResponse(true, 'Book Appointment successfully!', $appointmentDetails);
    }

}