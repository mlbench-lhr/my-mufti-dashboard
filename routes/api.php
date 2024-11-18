<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPassword;
use App\Http\Controllers\Event\EventController;
use App\Http\Controllers\Mufti\MuftiController;
use App\Http\Controllers\Mufti\MuftiDegrees;
use App\Http\Controllers\Notification\UserNotification;
use App\Http\Controllers\Profile\EditProfile;
use App\Http\Controllers\Question\QuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Apis
Route::controller(AuthController::class)->group(function () {
    Route::post('signUp', 'sign_up')->name('signUp');
    Route::post('login', 'sign_in')->name('login');
    Route::post('socialLoginSignUp', 'social_login_signup')->name('socialLoginSignUp');
    Route::post('updateDeviceId', 'update_device_id')->name('updateDeviceId');
    Route::post('logout', 'logout')->name('logout');
});

// forgot password
Route::controller(ForgotPassword::class)->group(function () {
    Route::post('generateOTP', 'generate_otp')->name('generateOTP');
    Route::post('verifyOTP', 'verify_otp')->name('verifyOTP');
    Route::post('resetPassword', 'reset_password')->name('resetPassword');
});

Route::group([
    'prefix' => 'profile',
], function () {
    Route::post('/myProfile', [EditProfile::class, 'my_profile']);
    Route::put('/profileImage', [EditProfile::class, 'edit_profile_image']);
    Route::put('/updateUsername', [EditProfile::class, 'update_username']);
    Route::put('/updatePassword', [EditProfile::class, 'update_password']);
    Route::post('/helpFeedback', [EditProfile::class, 'help_feedback']);
    Route::post('/delete', [EditProfile::class, 'delete_account']);
    Route::post('/requestForDeleteAccount', [EditProfile::class, 'request_for_delete_account']);
    Route::post('/userQueries', [EditProfile::class, 'my_queries']);
    Route::post('/userAllQueries', [EditProfile::class, 'my_all_queries']);
    Route::post('/askForMe', [EditProfile::class, 'ask_for_me']);
    Route::post('/questionAcceptDecline', [EditProfile::class, 'question_accept_decline']);
    Route::post('/bookAnAppointment', [EditProfile::class, 'book_an_appointment']);
    Route::post('/myAppointments', [EditProfile::class, 'my_appointments']);
    Route::post('/userSaveEvents', [EventController::class, 'user_save_events']);
    Route::post('/paymentRecordtest', [AuthController::class, 'payment_record_test']);

});

Route::group([
    'prefix' => 'mufti',
], function () {
    Route::post('becomeMufti', [MuftiController::class, 'request_to_become_mufti']);
    Route::post('searchScholar', [MuftiController::class, 'search_scholar']);
    Route::post('muftiDegrees', [MuftiDegrees::class, 'mufti_all_degrees']);
    Route::post('getDegree', [MuftiDegrees::class, 'get_single_degree']);
    Route::post('addDegree', [MuftiDegrees::class, 'add_degree']);
    Route::put('updateDegree', [MuftiDegrees::class, 'update_degree']);
    Route::post('deleteDegree', [MuftiDegrees::class, 'delete_degree']);
});

Route::group([
    'prefix' => 'question',
], function () {
    Route::post('postQuestion', [QuestionController::class, 'post_question']);
    Route::post('voteQuestion', [QuestionController::class, 'vote_on_question']);
    Route::post('allQuestion', [QuestionController::class, 'all_question']);
    Route::post('/userAllPublicQuestions', [QuestionController::class, 'User_AllPublicQuestions']);
    Route::post('questionDetail', [QuestionController::class, 'question_detail']);
    Route::post('addComment', [QuestionController::class, 'add_comment']);
    Route::post('scholarReply', [QuestionController::class, 'scholar_reply']);
    Route::post('postGeneralQuestion', [QuestionController::class, 'post_general_question']);
    Route::post('postFiqaWiseQuestion', [QuestionController::class, 'post_fiqa_wise_question']);
    Route::post('reportQuestion', [QuestionController::class, 'report_question']);
    Route::post('delete-all-private-questions', [QuestionController::class, 'deleteAllPrivateQuestionsByUserIds']);
});

Route::group([
    'prefix' => 'event',
], function () {
    Route::post('addEvent', [EventController::class, 'add_event']);
    Route::patch('updateEvent', [EventController::class, 'update_event']);
    Route::post('eventDetail', [EventController::class, 'event_detail']);

    Route::post('pastUpcomingEvents', [EventController::class, 'past_upcoming_events']);
    Route::post('allPastUpcomingEvents', [EventController::class, 'all_past_upcoming_events']);

    Route::post('myPastUpcomingRequestedEvents', [EventController::class, 'my_past_upcoming_requested_events']);
    Route::post('myAllPastUpcomingRequestedEvents', [EventController::class, 'my_all_past_upcoming_requested_events']);

    Route::post('addQuestionOnEvent', [EventController::class, 'add_question_on_event']);
    Route::post('addAnswerOnEvent', [EventController::class, 'add_answer_on_event']);
    Route::post('savaUnsaveEvent', [EventController::class, 'sava_unsave_event']);
    Route::post('searchEvent', [EventController::class, 'search_event']);
    Route::post('allQuestionsBelongstoEvents', [EventController::class, 'all_questions_belongs_to_events']);
    Route::post('allCategoryBelongstoEvents', [EventController::class, 'all_category_belongs_to_events']);
    Route::post('deleteEvent', [EventController::class, 'delete_event']);
    Route::post('addEventScholars', [EventController::class, 'add_event_scholars']);
    Route::post('removeEventScholar', [EventController::class, 'remove_event_scholar']);

    Route::post('likeDislikeEventQuestion', [EventController::class, 'like_dislike_event_question']);
});

Route::group([
    'prefix' => 'notification',
], function () {
    Route::post('userAllNotification', [UserNotification::class, 'user_all_notification']);
    Route::post('deleteNotification', [UserNotification::class, 'delete_notification']);
    Route::post('textNotification', [UserNotification::class, 'text_notification']);
    Route::post('newTextNotification', [UserNotification::class, 'new_text_notification']);
    Route::post('messageNotification', [UserNotification::class, 'message_notification']);
});
