<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\EventsAndApptController;
use App\Http\Controllers\Frontend\QuestionsController;
use App\Http\Controllers\Frontend\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'login']);
    Route::get('Dashboard', [DashboardController::class, 'dashboard'])->name('Dashboard');

    // users & scholars
    Route::get('DeletionRequests', [UserController::class, 'deletion_requests'])->name('DeletionRequests');
    Route::get('getDeletionRequests', [UserController::class, 'get_deletion_requests'])->name('getDeletionRequests');
    Route::post('rejectRequestDeletion', [UserController::class, 'reject_request_deletion'])->name('rejectRequestDeletion');
    Route::get('acceptRequestDeletion/{id}', [UserController::class, 'accept_request_deletion']);


    // users & scholars
    Route::get('AllUsers', [UserController::class, 'all_users'])->name('AllUsers');
    Route::get('getUsers', [UserController::class, 'get_all_users'])->name('getUsers');

    Route::get('AllScholars', [UserController::class, 'all_scholars'])->name('AllScholars');
    Route::get('getScholars', [UserController::class, 'get_all_scholars'])->name('getScholars');

    Route::get('AllLifeCoach', [UserController::class, 'all_lifecoach'])->name('AllLifeCoach');
    Route::get('getLifecoach', [UserController::class, 'get_all_lifecoach'])->name('getLifecoach');

    Route::get('ScholarsRequests', [UserController::class, 'all_scholar_request'])->name('ScholarsRequests');
    Route::get('getScholarRequests', [UserController::class, 'get_all_scholar_request'])->name('getScholarRequests');
    Route::get('ScholarRequest/Detail/{id}', [UserController::class, 'scholar_request_detail'])->name('ScholarRequest/Detail');
    Route::get('Approve/{id}', [UserController::class, 'approve_request'])->name('Approve');
    Route::post('Reject', [UserController::class, 'reject_request'])->name('Reject');


    Route::get('UserDetail/PublicQuestions/{id}', [UserController::class, 'user_detail'])->name('UserDetail/PublicQuestions');
    Route::get('ScholarDetail/PublicQuestions/{id}', [UserController::class, 'user_detail'])->name('ScholarDetail/PublicQuestions');
    Route::get('LifeCoachDetail/PublicQuestions/{id}', [UserController::class, 'user_detail'])->name('LifeCoachDetail/PublicQuestions');

    Route::get('getUserPublicQuestions/{id}', [UserController::class, 'get_public_questions_posted_by_user'])->name('getUserPublicQuestions');

    Route::get('UserDetail/PrivateQuestions/{id}', [UserController::class, 'user_detail_private_questons'])->name('UserDetail/PrivateQuestions');
    Route::get('ScholarDetail/PrivateQuestions/{id}', [UserController::class, 'user_detail_private_questons'])->name('ScholarDetail/PrivateQuestions');
    Route::get('getUserPrivateQuestions/{id}', [UserController::class, 'get_private_questions_asked_by_user'])->name('getUserPrivateQuestions');

    Route::get('UserDetail/Appointments/{id}', [UserController::class, 'user_detail_appointments'])->name('UserDetail/Appointments');
    Route::get('ScholarDetail/Appointments/{id}', [UserController::class, 'user_detail_appointments'])->name('ScholarDetail/Appointments');
    Route::get('LifeCoachDetail/Appointments/{id}', [UserController::class, 'user_detail_appointments'])->name('LifeCoachDetail/Appointments');

    Route::get('getUserAppointments/{id}', [UserController::class, 'get_appointments_of_user'])->name('getUserAppointments');

    Route::get('UserDetail/UserEvents/{id}', [UserController::class, 'user_events'])->name('UserDetail/UserEvents');
    Route::get('ScholarDetail/UserEvents/{id}', [UserController::class, 'user_events'])->name('ScholarDetail/UserEvents');
    Route::get('getUserEvents/{id}', [UserController::class, 'get_user_events'])->name('getUserEvents');

    Route::get('UserDetail/UserEventsRequest/{id}', [UserController::class, 'user_events_requests'])->name('UserDetail/UserEventsRequest');
    Route::get('ScholarDetail/UserEventsRequest/{id}', [UserController::class, 'user_events_requests'])->name('ScholarDetail/UserEventsRequest');
    Route::get('LifeCoachDetail/UserEventsRequest/{id}', [UserController::class, 'user_events_requests'])->name('LifeCoachDetail/UserEventsRequest');

    
    Route::get('getUserEventsRequests/{id}', [UserController::class, 'get_user_events_requests'])->name('getUserEventsRequests');


    Route::get('UserDetail/AskedFromScholar/{id}', [UserController::class, 'user_detail_asked_from_me'])->name('UserDetail/AskedFromScholar');
    Route::get('getUserAskedFromMe/{id}', [UserController::class, 'get_asked_from_me'])->name('getUserAskedFromMe');
    Route::get('UserDetail/Degrees/{id}', [UserController::class, 'user_detail_degrees'])->name('UserDetail/Degrees');

    Route::get('LifeCoachDetail/Degrees/{id}', [UserController::class, 'user_detail_degrees'])->name('LifeCoachDetail/Degrees');


    Route::get('DeleteUser/{id}', [UserController::class, 'delete_user'])->name('DeleteUser');


    //Public Questions
    Route::get('PublicQuestions', [QuestionsController::class, 'all_public_questions'])->name('PublicQuestions');
    Route::get('getPublicQuestions', [QuestionsController::class, 'get_all_public_questions'])->name('getPublicQuestions');
    Route::get('PublicQuestionDetail/{id}', [QuestionsController::class, 'public_question_detail'])->name('PublicQuestionDetail');
    Route::get('getQuestionComments/{id}', [QuestionsController::class, 'get_question_comments'])->name('getQuestionComments');
    Route::get('DeletePublicQuestion/{id}', [QuestionsController::class, 'delete_public_question'])->name('DeletePublicQuestion');

    //Reported Questions
    Route::get('ReportedQuestions', [QuestionsController::class, 'all_reported_questions'])->name('ReportedQuestions');
    Route::get('getReportedQuestions', [QuestionsController::class, 'get_all_reported_questions'])->name('getReportedQuestions');
    Route::get('ReportedQuestionDetail/{id}/{reportedId}', [QuestionsController::class, 'reported_question_detail'])->name('ReportedQuestionDetail');
    Route::get('/question/{id}/reports', [QuestionsController::class, 'get_question_reports'])->name('getQuestionReports');



    //Private Questions
    Route::get('PrivateQuestions', [QuestionsController::class, 'all_private_questions'])->name('PrivateQuestions');
    Route::get('getPrivateQuestions', [QuestionsController::class, 'get_all_private_questions'])->name('getPrivateQuestions');
    Route::get('PrivateQuestionDetail/{id}', [QuestionsController::class, 'private_question_detail'])->name('PrivateQuestionDetail');
    Route::get('DeletePrivateQuestion/{id}', [QuestionsController::class, 'delete_private_question'])->name('DeletePrivateQuestion');

    // Route for creating an admin reply
    Route::post('/admin/reply', [QuestionsController::class, 'adminReply'])->name('admin.reply');
    Route::post('/admin/approve', [QuestionsController::class, 'approveReply'])->name('admin.approve');
    Route::post('/admin/decline', [QuestionsController::class, 'declineReply'])->name('admin.decline');

    // Route for editing an admin reply
    Route::post('/admin/reply/edit', [QuestionsController::class, 'editAdminReply'])->name('admin.reply.edit');

    // Route for deleting an admin reply
    Route::post('/admin/reply/delete', [QuestionsController::class, 'deleteAdminReply'])->name('admin.reply.delete');
    // Appointments & Events
    Route::get('AllAppointments', [EventsAndApptController::class, 'all_appointments'])->name('AllAppointments');
    Route::get('getAppts', [EventsAndApptController::class, 'get_all_appointments'])->name('getAppts');

    Route::get('AllAppointments/LifeCoach', [EventsAndApptController::class, 'all_lifeCoach_appointments'])->name('AllAppointments/LifeCoach');
    Route::get('getLifeCoachAppts', [EventsAndApptController::class, 'get_all_lifeCoach_appointments'])->name('getLifeCoachAppts');

    Route::get('AppointmentDetail/{id}', [EventsAndApptController::class, 'appointment_detail'])->name('AppointmentDetail');

    Route::get('AllEvents', [EventsAndApptController::class, 'all_events'])->name('AllEvents');
    Route::get('getEvents', [EventsAndApptController::class, 'get_all_events'])->name('getEvents');
    Route::get('RequestedEvents', [EventsAndApptController::class, 'all_requested_events'])->name('RequestedEvents');
    Route::get('getRequestedEvents', [EventsAndApptController::class, 'get_all_requested_events'])->name('getRequestedEvents');
    Route::get('EventRequestApprove/{id}', [EventsAndApptController::class, 'approve_request'])->name('EventRequestApprove');
    Route::post('EventRequestDecline', [EventsAndApptController::class, 'reject_request'])->name('EventRequestDecline');


    Route::get('EventDetail/{id}', [EventsAndApptController::class, 'event_detail'])->name('EventDetail');
    Route::get('getEventsQuestions/{id}', [EventsAndApptController::class, 'get_event_questions'])->name('getEventsQuestions');
    Route::get('EventQuestionDetail/{id}', [EventsAndApptController::class, 'event_question_detail'])->name('EventQuestionDetail');
    Route::get('getEventScholars/{id}', [EventsAndApptController::class, 'get_event_questions'])->name('getEventScholars');
});

// Auth module
Route::post('loginn', [AdminController::class, 'loginn']);
Route::get('logout', [AdminController::class, 'flush']);
Route::get('/fogetPassword', [AdminController::class, 'forget']);
Route::get('/resetPassword', [AdminController::class, 'reset']);
Route::get('resetAdminPassword', [AdminController::class, 'reset_password']);
Route::get('fogetPasswordOTP', [AdminController::class, 'generate_otp']);
Route::get('verifyOTP', [AdminController::class, 'verify_otp']);


Route::get('questions/{id?}', [QuestionsController::class, 'show']);
