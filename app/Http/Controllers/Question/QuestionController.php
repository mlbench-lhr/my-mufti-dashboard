<?php

namespace App\Http\Controllers\Question;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminReply;
use App\Models\Interest;
use App\Models\Notification;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use App\Models\ReportQuestion as ReportQuestions;
use App\Models\ScholarReply;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function post_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'question_category' => 'required',
            'question' => 'required',
            'time_limit' => 'required',
            'voting_option' => 'required',
            'user_info' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $data = [
            'user_id' => $request->user_id,
            'question_category' => $request->question_category,
            'question' => $request->question,
            'time_limit' => $request->time_limit,
            'voting_option' => $request->voting_option,
            'user_info' => $request->user_info,
        ];

        $question = Question::create($data);

        $question = Question::where('id', $question->id)->first();

        $totalYesVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 1])->count();
        $question->totalYesVote = $totalYesVote;
        $totalNoVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 2])->count();
        $question->totalNoVote = $totalNoVote;

        $currentUserVote = QuestionVote::where(['question_id' => $request->question_id, 'user_id' => $request->user_id])->first();
        if (!$currentUserVote) {
            $question->current_user_vote = 0;
        } else if ($currentUserVote->vote == 1) {
            $question->current_user_vote = 1;
        } else if ($currentUserVote->vote == 2) {
            $question->current_user_vote = 2;
        }

        $question->user_detail = User::where('id', $question->user_id)->select('name', 'image')->first();
        $question->comments = QuestionComment::with('user_detail')->where('question_id', $question->id)->get();
        $scholar_reply = ScholarReply::with('user_detail')->where('question_id', $question->id)->first();

        if ($scholar_reply) {
            $question->scholar_reply = $scholar_reply;
        } else {
            $question->scholar_reply = (object) [];
        }

        if ($user->mufti_status == 2) {
            $message = "Mufti " . $user->name . " posted a new question.";
        } else {
            $message = $user->name . " posted a new question.";
        }

        $user_id = $user->id;
        $type = "posted question";

        ActivityHelper::store_avtivity($user_id, $message, $type);
        $response = [
            'status' => true,
            'message' => 'Added question successfully!',
            'data' => $question,
        ];
        return response()->json($response, 200);
    }

    public function vote_on_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'question_id' => 'required',
            'vote' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $question = Question::where('id', $request->question_id)->first();

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $questionVote = QuestionVote::where(['question_id' => $request->question_id, 'user_id' => $request->user_id, 'vote' => $request->vote])->first();
        $voteQuestion = QuestionVote::where(['question_id' => $request->question_id, 'user_id' => $request->user_id])->first();

        $questionId = $request->question_id;

        if ($questionVote) {
            return ResponseHelper::jsonResponse(false, 'Voted Already at this question');
        } else if ($voteQuestion) {
            $data = [
                'vote' => $request->vote,
            ];
            $voteQuestion->update($data);
            $userData = User::where('id', $question->user_id)->first();
            if ($question->user_id != $request->user_id) {
                $device_id = $userData->device_id;
                $title = "Public Question Update";
                $body = 'User ' . $user->name . ' has vote on your posted public question.';
                $messageType = "Public Question Update";
                $otherData = "Public Question Update";
                $notificationType = "2";

                if ($device_id != "") {
                    $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType, $questionId);
                }
            }

            return ResponseHelper::jsonResponse(true, 'Update Voted question successfully!');
        } else {
            $data = [
                'user_id' => $request->user_id,
                'question_id' => $request->question_id,
                'vote' => $request->vote,
            ];
            QuestionVote::create($data);

            $userData = User::where('id', $question->user_id)->first();
            if ($question->user_id != $request->user_id) {
                $device_id = $userData->device_id;
                $title = "Public Question Update";
                $body = 'User ' . $user->name . ' has vote on your posted public question.';
                $messageType = "Public Question Update";
                $otherData = "Public Question Update";
                $notificationType = "2";

                if ($device_id != "") {
                    $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType, $questionId);
                }
            }

            return ResponseHelper::jsonResponse(true, 'Voted question successfully!');
        }
    }
    public function User_AllPublicQuestions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'search' => 'nullable|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $page = $request->input('page', 1);
        $perPage = 10;

        $baseQuery = Question::withCount([
            'votes as totalYesVote' => function ($query) {
                $query->where('vote', 1);
            },
            'votes as totalNoVote' => function ($query) {
                $query->where('vote', 2);
            },
            'comments as comments_count',
        ])
            ->with('user_detail')
            ->withCount('reports')
            ->having('reports_count', '<', 10)
            ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'DESC');

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $baseQuery->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $totalPages = ceil($baseQuery->count() / $perPage);

        $questions = $baseQuery->paginate($perPage, ['*'], 'page', $page);

        if ($questions->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No questions found!',
                'totalpages' => (int) 0,
                'data' => [],
            ], 200);
        }

        $questions->each(function ($question) use ($request) {
            $question->current_user_vote = QuestionVote::where([
                'question_id' => $question->id,
                'user_id' => $request->user_id,
            ])->value('vote') ?? 0;

            $question->totalYesVote = (int) $question->totalYesVote;
            $question->totalNoVote = (int) $question->totalNoVote;
            $question->comments_count = (int) $question->comments_count;

            unset($question->reports_count);
        });
        $userLikedQuestionIds = ReportQuestions::where('user_id', $request->user_id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->pluck('question_id')
            ->toArray();

        $questions->transform(function ($question) use ($userLikedQuestionIds) {
            $question->is_reported = in_array($question->id, $userLikedQuestionIds);
            return $question;
        });

        return response()->json([
            'status' => true,
            'message' => 'User All Public questions!',
            'totalpages' => $totalPages,
            'data' => $questions->items(),
        ], 200);
    }

    public function all_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'search' => 'nullable|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $page = $request->input('page', 1);
        $perPage = 10;

        $baseQuery = Question::withCount([
            'votes as totalYesVote' => function ($query) {
                $query->where('vote', 1);
            },
            'votes as totalNoVote' => function ($query) {
                $query->where('vote', 2);
            },
            'comments as comments_count',
        ])
            ->with('user_detail')
            ->withCount('reports')
            ->having('reports_count', '<', 10)
            ->orderBy('created_at', 'DESC');

        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $baseQuery->where('question', 'LIKE', '%' . $searchTerm . '%');
        }

        $totalPages = ceil($baseQuery->count() / $perPage);

        $questions = $baseQuery->paginate($perPage, ['*'], 'page', $page);

        if ($questions->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No questions found!',
                'totalpages' => (int) 0,
                'data' => [],
            ], 200);
        }

        $questions->each(function ($question) use ($request) {
            $question->current_user_vote = QuestionVote::where([
                'question_id' => $question->id,
                'user_id' => $request->user_id,
            ])->value('vote') ?? 0;

            $question->totalYesVote = (int) $question->totalYesVote;
            $question->totalNoVote = (int) $question->totalNoVote;
            $question->comments_count = (int) $question->comments_count;

            unset($question->reports_count);
        });
        $userLikedQuestionIds = ReportQuestions::where('user_id', $request->user_id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->pluck('question_id')
            ->toArray();

        $questions->transform(function ($question) use ($userLikedQuestionIds) {
            $question->is_reported = in_array($question->id, $userLikedQuestionIds);
            return $question;
        });

        return response()->json([
            'status' => true,
            'message' => 'All Public questions!',
            'totalpages' => $totalPages,
            'data' => $questions->items(),
        ], 200);
    }

    public function question_detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'question_id' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $question = Question::where('id', $request->question_id)->first();

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $question = Question::where('id', $question->id)->first();

        $totalYesVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 1])->count();
        $question->totalYesVote = $totalYesVote;
        $totalNoVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 2])->count();
        $question->totalNoVote = $totalNoVote;

        $question->is_reported = ReportQuestions::where(['user_id' => $request->user_id, 'question_id' => $request->question_id])->exists();

        $currentUserVote = QuestionVote::where(['question_id' => $request->question_id, 'user_id' => $request->user_id])->first();
        if (!$currentUserVote) {
            $question->current_user_vote = 0;
        } else if ($currentUserVote->vote == 1) {
            $question->current_user_vote = 1;
        } else if ($currentUserVote->vote == 2) {
            $question->current_user_vote = 2;
        }

        $question->user_detail = User::where('id', $question->user_id)->select('name', 'image')->first();

        $page = $request->input('page', 1);
        $perPage = 20;

        $baseQuery = QuestionComment::with('user_detail')
            ->where('question_id', $question->id);

        $totalComments = $baseQuery->count();
        $totalPages = ceil($totalComments / $perPage);

        $paginatedComments = $baseQuery->paginate($perPage, ['*'], 'page', $page);

        $question->comments = $paginatedComments->items();

        $scholar_reply = ScholarReply::with('user_detail')->where('question_id', $question->id)->first();
        $question->scholar_reply = $scholar_reply ? $scholar_reply : (object) [];

        $admin_reply = AdminReply::where([
            'question_id' => $question->id,
            'question_type' => 'public',
        ])->first();

        if ($admin_reply) {
            $question->admin_reply = (object) [
                'id' => $admin_reply->id,
                'question_id' => $admin_reply->question_id,
                'user_id' => $admin_reply->user_id,
                'reply' => $admin_reply->reply,
                'created_at' => $admin_reply->created_at,
                'updated_at' => $admin_reply->updated_at,
                'user_detail' => (object) [
                    'id' => $admin_reply->user_id,
                    'name' => 'My Mufti Admin',
                    'image' => '',
                    'fiqa' => '',
                ],
            ];
        } else {
            $question->admin_reply = (object) [];
        }

        $response = [
            'status' => true,
            'message' => 'Question detail!',
            'total_pages' => $totalPages,
            'data' => $question,
        ];

        return response()->json($response, 200);
    }

    public function add_comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'question_id' => 'required',
            'comment' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $question = Question::where('id', $request->question_id)->first();

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $questionId = $request->question_id;

        $data = [
            'user_id' => $request->user_id,
            'question_id' => $request->question_id,
            'comment' => $request->comment,
        ];
        if ($question->user_id != $request->user_id) {
            $userData = User::where('id', $question->user_id)->first();
            $device_id = $userData->device_id;
            $title = "Public Question Update";
            $body = 'User ' . $user->name . ' has comment on your posted public question.';
            $messageType = "Public Question Update";
            $otherData = "Public Question Update";
            $notificationType = "2";
            if ($device_id != "") {
                $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType, $questionId);
            }
        }
        $comment = QuestionComment::create($data);

        return ResponseHelper::jsonResponse(true, 'Comment added successfully!');
    }

    public function scholar_reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'question_id' => 'required',
            'reply' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        if ($request->user_id != 9) {
            return ResponseHelper::jsonResponse(false, 'Only Mufti Omer can reply to question.');
        }

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }
        $question = Question::where('id', $request->question_id)->first();

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $questionId = $request->question_id;

        $data = [
            'user_id' => $request->user_id,
            'question_id' => $request->question_id,
            'reply' => $request->reply,
        ];

        ScholarReply::updateOrCreate(
            ['user_id' => $request->user_id, 'question_id' => $request->question_id],
            $data
        );

        $userData = User::where('id', $question->user_id)->first();
        $device_id = $userData->device_id;
        $title = "Public Question Update";
        $body = 'Scholar ' . $user->name . ' has reply on your question.';
        $messageType = "Public Question Update";
        $otherData = "Public Question Update";
        $notificationType = "2";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType, $questionId);
        }

        return ResponseHelper::jsonResponse(true, 'Reply added successfully!');
    }

    // public function post_general_question(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required',
    //         'mufti_id' => 'required',
    //         'question' => 'required',
    //     ]);

    //     $validationError = ValidationHelper::handleValidationErrors($validator);
    //     if ($validationError !== null) {
    //         return $validationError;
    //     }

    //     $user = User::where('id', $request->user_id)->first();

    //     if (!$user) {
    //         return ResponseHelper::jsonResponse(false, 'User Not Found');
    //     }
    //     $mufti = User::where(['id' => $request->mufti_id, 'mufti_status' => 2])->first();

    //     if (!$mufti) {
    //         return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
    //     }
    //     $categories = Interest::where('user_id', $request->mufti_id)->pluck('interest')->toArray();
    //     $data = [
    //         'user_id' => $request->user_id,
    //         'question' => $request->question,
    //         'fiqa' => "General",
    //         'category' => $categories,
    //     ];

    //     $question = UserQuery::create($data);
    //     if ($request->mufti_id == 9) {
    //         $data2 = [
    //             'query_id' => $question->id,
    //             'user_id' => $request->user_id,
    //             'mufti_id' => 9,
    //             'question' => $request->question,
    //         ];
    //         $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();

    //         $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);
    //         $device_id = $user->device_id;
    //         $title = "Question Request Sent";

    //         $notiBody = 'Your request for private question to ' . $mufti->name . ' has been sent.';
    //         $body = 'Your request for private question to ' . $mufti->name . ' has been sent.';
    //         $messageType = "Question Request Sent";
    //         $otherData = "personal question";
    //         $notificationType = "1";

    //         if ($device_id != "") {
    //             $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
    //         }

    //         $data = [
    //             'user_id' => $user->id,
    //             'title' => $title,
    //             'body' => $body,
    //         ];
    //         Notification::create($data);
    //         UserAllQuery::create($data2);
    //         return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //     } elseif ($request->mufti_id != 9 && $request->user_id != 9) {

    //         $data1 = [
    //             'query_id' => $question->id,
    //             'user_id' => $request->user_id,
    //             'mufti_id' => $request->mufti_id,
    //             'question' => $request->question,
    //         ];
    //         $data2 = [
    //             'query_id' => $question->id,
    //             'user_id' => $request->user_id,
    //             'mufti_id' => 9,
    //             'question' => $request->question,
    //         ];

    //         $this->send($mufti->device_id, "Asked Question", $user->name, $mufti->id);

    //         $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();

    //         $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);

    //         $device_id = $user->device_id;
    //         $title = "Question Request Sent";

    //         $notiBody = 'Your request for private question to ' . $mufti->name . ' has been sent.';
    //         $body = 'Your request for private question to ' . $mufti->name . ' has been sent.';
    //         $messageType = "Question Request Sent";
    //         $otherData = "personal question";
    //         $notificationType = "1";

    //         if ($device_id != "") {
    //             $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
    //         }

    //         $data = [
    //             'user_id' => $user->id,
    //             'title' => $title,
    //             'body' => $body,
    //         ];
    //         Notification::create($data);

    //         UserAllQuery::create($data1);
    //         UserAllQuery::create($data2);

    //         return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //     } elseif ($request->mufti_id != 9 && $request->user_id == 9) {
    //         $data1 = [
    //             'query_id' => $question->id,
    //             'user_id' => $request->user_id,
    //             'mufti_id' => $request->mufti_id,
    //             'question' => $request->question,
    //         ];

    //         $this->send($mufti->device_id, "Asked Question", $user->name, $mufti->id);

    //         $device_id = $user->device_id;
    //         $title = "Question Request Sent";

    //         $notiBody = 'Your request for private question to ' . $mufti->name . ' has been sent.';
    //         $body = 'Your request for private question to ' . $mufti->name . ' has been sent.';
    //         $messageType = "Question Request Sent";
    //         $otherData = "personal question";
    //         $notificationType = "1";

    //         if ($device_id != "") {
    //             $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
    //         }

    //         $data = [
    //             'user_id' => $user->id,
    //             'title' => $title,
    //             'body' => $body,
    //         ];
    //         Notification::create($data);
    //         UserAllQuery::create($data1);
    //         return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //     }
    // }

    public function post_general_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'mufti_id' => 'required',
            'question' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User  Not Found');
        }

        $mufti_id = 9;
        $mufti = User::where(['id' => $mufti_id, 'mufti_status' => 2])->first();
        if (!$mufti) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $categories = Interest::where('user_id', $mufti_id)->pluck('interest')->toArray();
        $data = [
            'user_id' => $request->user_id,
            'question' => $request->question,
            'fiqa' => "General",
            'category' => $categories,
        ];

        $question = UserQuery::create($data);

        $data1 = [
            'query_id' => $question->id,
            'user_id' => $request->user_id,
            'mufti_id' => $mufti_id,
            'question' => $request->question,
        ];

        $this->send($mufti->device_id, "Asked Question", $user->name, $mufti->id);

        $device_id = $user->device_id;
        $title = "Question Request Update";
        $body = 'Your request for a private question to ' . $mufti->name . ' has been sent.';
        $messageType = "Question Request Update";
        $otherData = "Question Request Update";
        $notificationType = "1";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $notificationData = [
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
        ];
        Notification::create($notificationData);

        $user_id = $user->id;
        $type = "added private question";
        $message = $user->name . " added a private question.";

        ActivityHelper::store_avtivity($user_id, $message, $type);

        UserAllQuery::create($data1);

        return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    }
    // public function post_fiqa_wise_question(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required',
    //         'fiqa' => 'required',
    //         'question' => 'required',
    //         'category' => 'required',
    //     ]);

    //     $validationError = ValidationHelper::handleValidationErrors($validator);
    //     if ($validationError !== null) {
    //         return $validationError;
    //     }

    //     $user = User::where('id', $request->user_id)->first();
    //     if (!$user) {
    //         return ResponseHelper::jsonResponse(false, 'User Not Found');
    //     }

    //     $muftisUser = User::where('mufti_status', 2)->inRandomOrder()->limit(5)->pluck('id')->toArray();

    //     $valueToRemove = 9;

    //     $filteredArray = array_filter($muftisUser, function ($item) use ($valueToRemove) {
    //         return $item !== $valueToRemove;
    //     });

    //     $muftisUser = array_values($filteredArray);

    //     $muftis = Interest::whereIn('user_id', $muftisUser)->where(['fiqa' => $request->fiqa, 'interest' => $request->category])->distinct()->inRandomOrder()->limit(5)->pluck('user_id')->toArray();

    //     $data = [
    //         'user_id' => $request->user_id,
    //         'question' => $request->question,
    //         'fiqa' => $request->fiqa,
    //         'category' => (array) $request->category,
    //     ];

    //     $muftiDeviceId = User::whereIn('id', $muftisUser)->select('id', 'device_id')->get();

    //     $muftiDeviceId->each(function ($value) use ($user) {
    //         $this->send($value->device_id, "Asked Question", $user->name, $value->id);
    //     });

    //     $question = UserQuery::create($data);

    //     if (count($muftis) <= 0) {
    //         if ($request->user_id == 9) {
    //             return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //         }

    //         $data2 = [
    //             'query_id' => $question->id,
    //             'user_id' => $request->user_id,
    //             'mufti_id' => 9,
    //             'question' => $request->question,
    //         ];

    //         $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();
    //         $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);

    //         UserAllQuery::create($data2);

    //         return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //     }

    //     if ($request->user_id == 9) {

    //         $questionId = $question->id;
    //         collect($muftis)->map(function ($value) use ($request, $questionId) {
    //             return [
    //                 'query_id' => $questionId,
    //                 'user_id' => $request->user_id,
    //                 'mufti_id' => $value,
    //                 'question' => $request->question,
    //             ];
    //         })->each(function ($data) {
    //             UserAllQuery::create($data);
    //         });

    //         return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //     } else {
    //         // dd('numan');
    //         $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();

    //         $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);

    //         $data2 = [
    //             'query_id' => $question->id,
    //             'user_id' => $request->user_id,
    //             'mufti_id' => 9,
    //             'question' => $request->question,
    //         ];
    //         UserAllQuery::create($data2);

    //         $questionId = $question->id;
    //         collect($muftis)->map(function ($value) use ($request, $questionId) {
    //             return [
    //                 'query_id' => $questionId,
    //                 'user_id' => $request->user_id,
    //                 'mufti_id' => $value,
    //                 'question' => $request->question,
    //             ];
    //         })->each(function ($data) {
    //             UserAllQuery::create($data);
    //         });

    //         $device_id = $user->device_id;
    //         $title = "Question Request Sent";
    //         $notiBody = 'Your request for private question to has been sent.';
    //         $body = 'Your request for private question to has been sent.';
    //         $messageType = "Question Request Sent";
    //         $otherData = "personal question";
    //         $notificationType = "1";

    //         if ($device_id != "") {
    //             $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
    //         }

    //         return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    //     }
    // }

    public function post_fiqa_wise_question(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'fiqa' => 'required',
            'question' => 'required',
            'category' => 'required',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $mufti_id = 9;
        $mufti = User::where(['id' => $mufti_id, 'mufti_status' => 2])->first();
        if (!$mufti) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }

        $data = [
            'user_id' => $request->user_id,
            'question' => $request->question,
            'fiqa' => $request->fiqa,
            'category' => (array) $request->category,
        ];

        $question = UserQuery::create($data);

        $data2 = [
            'query_id' => $question->id,
            'user_id' => $request->user_id,
            'mufti_id' => $mufti_id,
            'question' => $request->question,
        ];

        $this->send($mufti->device_id, "Asked Question", $user->name, $mufti->id);

        UserAllQuery::create($data2);

        $device_id = $user->device_id;
        $title = "Question Request Update";
        $body = 'Your request for a private question to ' . $mufti->name . ' has been sent.';
        $messageType = "Question Request Update";
        $otherData = "Question Request Update";
        $notificationType = "1";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $notificationData = [
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
        ];
        Notification::create($notificationData);

        return ResponseHelper::jsonResponse(true, 'Added question successfully!');
    }

    public function report_question(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }

        $question = Question::find($request->question_id);

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $check = ReportQuestions::where([
            'user_id' => $request->user_id,
            'question_id' => $request->question_id,
        ])->first();

        if ($check) {
            return ResponseHelper::jsonResponse(false, 'Already Reported!');
        } else {
            $data = [
                'user_id' => $request->user_id,
                'question_id' => $request->question_id,
                'reason' => $request->reason,
            ];

            ReportQuestions::create($data);

            if ($user->mufti_status == 2) {
                $message = "Mufti " . $user->name . " reported public question.";
            } else {
                $message = $user->name . " reported public question.";
            }

            $user_id = $request->user_id;
            $type = "reported question";

            ActivityHelper::store_avtivity($user_id, $message, $type);

            return ResponseHelper::jsonResponse(true, 'Reported Successfully');
        }
    }

    public function send($deviceId, $title, $name, $muftiId)
    {
        $device_id = $deviceId;
        $title = $title;
        $notiBody = 'User' . ' ' . $name . ' wants to ask a question for you.';
        $body = 'User' . ' ' . $name . ' wants to ask a question for you.';
        $messageType = $title;
        $otherData = "Asked Question";
        $notificationType = "1";

        if ($device_id != "") {
            $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType);
        }

        $data = [
            'user_id' => $muftiId,
            'title' => $title,
            'body' => $body,
        ];
        Notification::create($data);
    }
    public function deleteAllPrivateQuestionsByUserIds(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
        ]);

        if (empty($request->user_ids)) {
            return response()->json(['message' => 'No user IDs provided.'], 200);
        }
        $ids = $request->user_ids;
        $userquestion = UserQuery::whereIn('user_id', $ids)->pluck('id')->toArray();
        UserAllQuery::whereIn('query_id', $userquestion)->delete();
        UserQuery::whereIn('user_id', $ids)->delete();
        return response()->json(['message' => 'All private questions for specified users have been deleted.'], 200);
    }
}
