<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminReply;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use App\Models\ReportQuestion;
use App\Models\ScholarReply;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Database;

class QuestionsController extends Controller
{

    protected $fcmService;
    protected $firebase;

    public function __construct(FcmService $fcmService, Database $firebase)
    {
        $this->fcmService = $fcmService;
        $this->firebase = $firebase;
    }

    public function all_public_questions()
    {
        $questions = Question::get();
        return view('frontend.PublicQuestions', compact('questions'));
    }
    public function get_all_public_questions(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = Question::count();
        $query = Question::with('user');

        if ($searchTerm) {
            $query->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'DESC');

        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('M d, Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function public_question_detail(Request $request)
    {
        $type = $request->flag;
        $user_id = $request->uId;
        $question_id = $request->id;
        $question = Question::where('id', $request->id)->first();

        $totalVote = QuestionVote::where('question_id', $question_id)->count();

        $totalYesVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 1])->count();
        if ($totalVote > 0) {
            $yesVotesPercentage = round(($totalYesVote / $totalVote) * 100, 0);
        } else {
            $yesVotesPercentage = 0;
        }
        $question->yesVotesPercentage = $yesVotesPercentage;

        $totalNoVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 2])->count();
        if ($totalVote > 0) {
            $noVotesPercentage = round(($totalNoVote / $totalVote) * 100, 0);
        } else {
            $noVotesPercentage = 0;
        }
        $question->noVotesPercentage = $noVotesPercentage;

        $question->user_detail = User::where('id', $question->user_id)->select('name', 'image', 'email', 'user_type')->first();

        $question->comments = QuestionComment::with('user_detail')->where('question_id', $question->id)->get();
        $scholar_reply = ScholarReply::with('user_detail.interests')->where('question_id', $question->id)->first();

        $question->scholar_reply = $scholar_reply;

        $isReplied = AdminReply::where([
            'question_id' => $question_id,
            'question_type' => 'public',
        ])->exists();

        return view('frontend.PublicQuestionDetail', compact('question', 'question_id', 'type', 'user_id', 'isReplied'));
    }

    public function get_question_comments(Request $request)
    {
        $searchTerm = $request->input('search');

        $userCount = QuestionComment::with('user')->where('question_id', $request->id)->count();
        $query = QuestionComment::with('user')->where('question_id', $request->id);

        $query->orderBy('created_at', 'DESC');

        $user = $query->paginate(3);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('M d, Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }
    public function get_question_reports(Request $request)
    {
        $searchTerm = $request->input('search');

        $reportCount = ReportQuestion::with('user')->where('question_id', $request->id)->count();

        $query = ReportQuestion::with('user')->where('question_id', $request->id);

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('reason', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('user_detail', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        $query->orderBy('created_at', 'DESC');

        $reports = $query->paginate(5);

        if ($reports->isEmpty()) {
            $reports = collect();
        }

        foreach ($reports as $report) {
            $report->reported_at = $report->created_at->format('M d, Y');
        }
        return response()->json([
            'reportCount' => $reportCount,
            'reports' => $reports,
        ]);
    }

    public function reported_question_detail(Request $request)
    {
        $type = $request->flag;
        $user_id = $request->uId;
        $question_id = $request->id;
        $reported_id = $request->reportedId;

        $question = Question::where('id', $question_id)->first();

        $reportedQuestion = ReportQuestion::where('id', $reported_id)->with('user_detail')->first();
        $totalVote = QuestionVote::where('question_id', $question_id)->count();
        $totalYesVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 1])->count();
        $totalNoVote = QuestionVote::where(['question_id' => $question->id, 'vote' => 2])->count();

        $question->yesVotesPercentage = $totalVote > 0 ? round(($totalYesVote / $totalVote) * 100, 0) : 0;
        $question->noVotesPercentage = $totalVote > 0 ? round(($totalNoVote / $totalVote) * 100, 0) : 0;

        $question->user_detail = User::where('id', $question->user_id)
            ->select('name', 'image', 'email', 'user_type')
            ->first();

        $question->comments = QuestionComment::with('user_detail')
            ->where('question_id', $question->id)
            ->get();

        $scholar_reply = ScholarReply::with('user_detail.interests')
            ->where('question_id', $question->id)
            ->first();

        $question->scholar_reply = $scholar_reply;

        return view('frontend.ReportedQuestionDetail', compact('question', 'reportedQuestion', 'question_id', 'type', 'user_id'));
    }

    public function delete_public_question(Request $request, $id)
    {
        $question = Question::where('id', $id)->first();
        $comments = QuestionComment::where('question_id', $id)->delete();
        $vote = QuestionVote::where('question_id', $id)->delete();
        $scholar_reply = ScholarReply::where('question_id', $id)->delete();
        ReportQuestion::where('question_id', $id)->delete();
        AdminReply::where([
            'question_id' => $id,
            'question_type' => 'public',
        ])->delete();

        $question->delete();

        if ($request->flag === "1") {
            return redirect('PublicQuestions');
        } else if ($request->flag === "3") {
            return redirect('ReportedQuestions');
        } else {
            return redirect("UserDetail/PublicQuestions/{$request->uId}");
        }
    }

    public function all_private_questions()
    {
        $questions = UserQuery::get();
        return view('frontend.PrivateQuestions', compact('questions'));
    }
    public function get_all_private_questions(Request $request)
    {
        $searchTerm = $request->input('search');
        $userCount = UserQuery::count();
        $query = UserQuery::with('all_question.mufti_detail.interests');

        if ($searchTerm) {
            $query->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $query->orderBy('created_at', 'DESC');
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('M d, Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public function private_question_detail(Request $request)
    {
        $type = $request->flag;
        $user_id = $request->uId;
        $question_id = $request->id;

        $detail = UserQuery::with('questioned_by')->where('id', $question_id)->first();

        if (!$detail) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $muftiOmarId = 9;

        $question_from = UserAllQuery::with('mufti_detail.interests')
            ->where('query_id', $question_id)
            ->where('mufti_id', $muftiOmarId)
            ->get();

        return view('frontend.PrivateQuestionDetail', compact('detail', 'question_id', 'question_from', 'type', 'user_id'));
    }

    public function delete_private_question(Request $request, $id)
    {
        $question = UserQuery::with('questioned_by')->where('id', $id)->first();
        $question_from = UserAllQuery::with('mufti_detail.interests')->where('query_id', $id)->delete();
        $question->delete();
        if ($request->flag === "1") {
            return redirect('PrivateQuestions');
        } else {
            return redirect("UserDetail/PrivateQuestions/{$request->uId}");
        }
    }

    public function show($id = null)
    {
        if (is_null($id)) {
            $id = 1;
        }

        $question = Question::withCount([
            'votes as totalYesVote' => function ($query) {
                $query->where('vote', 1);
            },
            'votes as totalNoVote' => function ($query) {
                $query->where('vote', 2);
            },
            'comments as comments_count',
        ])
            ->with('user_detail', 'comments.user_detail')
            ->find($id);

        if (!$question) {
            $message = 'The question you are looking for does not exist.';
            $html = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Question Not Found</title>
                </head>
                <body>
                    <h1>Question Not Found</h1>
                    <p>' . $message . '</p>
                </body>
                </html>';

            return response($html, 404)
                ->header('Content-Type', 'text/html');
        }

        return view('question', compact('question'));
    }

    public function all_reported_questions()
    {
        $reportedQuestions = ReportQuestion::with('user_detail', 'question.user_detail')->paginate(10);

        return view('frontend.AllReportedQuestions', compact('reportedQuestions'));
    }

    public function get_all_reported_questions(Request $request)
    {
        $searchTerm = $request->input('search');

        $userCount = ReportQuestion::distinct('question_id')->count();
        $data = ReportQuestion::distinct()->pluck('question_id');

        $query = Question::whereIn('id', $data)->with('user_detail');

        if ($searchTerm) {
            $query->whereHas('question', function ($subQuery) use ($searchTerm) {
                $subQuery->where('question', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $query->orderBy('created_at', 'desc');

        $reportedQuestions = $query->paginate(10);

        if ($reportedQuestions->isEmpty()) {
            return response()->json(['message' => 'No reported questions found', 'reportedQuestions' => []]);
        }

        foreach ($reportedQuestions as $row) {
            $row->registration_date = $row->created_at->format('M d, Y');
        }

        return response()->json(['userCount' => $userCount, 'reportedQuestions' => $reportedQuestions]);
    }

    public function adminReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $question = Question::with(['user', 'adminReply'])->find($request->question_id);
        if (!$question) {
            return redirect()->back()->withErrors(['error' => 'Question not found.']);
        }

        $user = $question->user;
        $questionId = $request->question_id;

        if ($request->filled('reply_id')) {
            $adminReply = AdminReply::where([
                'id' => $request->reply_id,
                'question_type' => 'public',
            ])->first();

            if (!$adminReply) {
                return redirect()->back()->withErrors(['error' => 'Reply not found or is not public.']);
            }

            $adminReply->reply = $request->reply;
            $adminReply->save();
        } else {
            $replyData = [
                'question_id' => $request->question_id,
                'user_id' => 0,
                'reply' => $request->reply,
                'question_type' => 'public',
            ];

            AdminReply::create($replyData);
            $userData = User::where('id', $question->user_id)->first();
            $device_id = $userData->device_id;
            $title = "Admin Replied";
            $body = 'My Mufti Admin has reply on your question.';
            $messageType = "Admin reply";
            $otherData = "Admin reply";
            $notificationType = "2";

            if ($device_id != "") {
                $this->fcmService->sendNotification($device_id, $title, $body, $messageType, $otherData, $notificationType, $questionId);
            }
        }
        return redirect()->to('/PublicQuestionDetail/' . $question->id . '?flag=1');
    }
    public function approveReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $replyData = [
            'question_id' => $request->query_id,
            'user_id' => 9,
            'reply' => $request->reply,
            'question_type' => 'private',
        ];
        AdminReply::create($replyData);

        $userQuery = UserAllQuery::where([
            'query_id' => $request->query_id,
            'mufti_id' => 9,
        ])->first();

        if ($userQuery) {
            $userQuery->status = 1;
            $userQuery->save();

            $userData = User::find($userQuery->user_id);

            $allMessagesData1 = (object) [
                'content_message' => $userQuery->question,
                'conversation_id' => '9+' . $userQuery->user_id,
                'date' => now()->format('d-m-Y H:i:s'),
                'is_read' => false,
                'receiver_id' => (string) $userQuery->user_id,
                'sender_id' => "9",
                'time_zone_id' => 'Asia/Karachi',
                'type' => 'text',
            ];

            $muftiId = 9;
            $userId = $userQuery->user_id;

            if ($muftiId < $userId) {
                $postKey = $muftiId . '+' . $userId;
            } else {
                $postKey = $userId . '+' . $muftiId;
            }

            $referencePath1 = 'All_Messages/' . $postKey;

            $this->firebase->getReference($referencePath1)
                ->push(json_decode(json_encode($allMessagesData1)));

            // For Mufti
            $inboxData1 = (object) [
                'chat_name' => $userData->name ?? "",
                'content_message' => $userQuery->question,
                'conversation_enable' => false,
                'conversation_id' => $postKey,
                'date' => now()->format('d-m-Y H:i:s'),
                'other_user_id' => (string) $userQuery->user_id,
                'read_count' => 0,
                'time_zone_id' => 'Asia/Karachi',
                'type' => 'text',
            ];

            // For User
            $inboxData2 = (object) [
                'chat_name' => "Mufti Omar",
                'content_message' => $userQuery->question,
                'conversation_enable' => false,
                'conversation_id' => $postKey,
                'date' => now()->format('d-m-Y H:i:s'),
                'other_user_id' => "9",
                'read_count' => 0,
                'time_zone_id' => 'Asia/Karachi',
                'type' => 'text',
            ];

            $referencePath2 = 'Inbox/' . '_' . $userQuery->user_id . '/' . $postKey;
            $this->firebase->getReference($referencePath2)
                ->set(json_decode(json_encode($inboxData2)));

            $referencePath3 = 'Inbox/_9/' . $postKey;
            $this->firebase->getReference($referencePath3)
                ->set(json_decode(json_encode($inboxData1)));

            // for admin reply

            $allMessagesData2 = (object) [
                'content_message' => $request->reply,
                'conversation_id' => '9+' . $userQuery->user_id,
                'date' => now()->format('d-m-Y H:i:s'),
                'is_read' => false,
                'receiver_id' => (string) $userQuery->user_id,
                'sender_id' => "9",
                'time_zone_id' => 'Asia/Karachi',
                'type' => 'text',
            ];
            // check ids
            if ($muftiId < $userId) {
                $postKey = $muftiId . '+' . $userId;
            } else {
                $postKey = $userId . '+' . $muftiId;
            }
            $referencePath4 = 'All_Messages/' . $postKey;
            $this->firebase->getReference($referencePath4)
                ->push(json_decode(json_encode($allMessagesData2)));

            // For Mufti
            $inboxData3 = (object) [
                'chat_name' => $userData->name ?? "",
                'content_message' => $request->reply,
                'conversation_enable' => false,
                'conversation_id' => $postKey,
                'date' => now()->format('d-m-Y H:i:s'),
                'other_user_id' => (string) $userQuery->user_id,
                'read_count' => 0,
                'time_zone_id' => 'Asia/Karachi',
                'type' => 'text',
            ];

            // For User
            $inboxData4 = (object) [
                'chat_name' => "Mufti Omar",
                'content_message' => $request->reply,
                'conversation_enable' => false,
                'conversation_id' => $postKey,
                'date' => now()->format('d-m-Y H:i:s'),
                'other_user_id' => "9",
                'read_count' => 0,
                'time_zone_id' => 'Asia/Karachi',
                'type' => 'text',
            ];

            $referencePath5 = 'Inbox/' . '_' . $userQuery->user_id . '/' . $postKey;
            $this->firebase->getReference($referencePath5)
                ->set(json_decode(json_encode($inboxData4)));

            $referencePath6 = 'Inbox/_9/' . $postKey;
            $this->firebase->getReference($referencePath6)
                ->set(json_decode(json_encode($inboxData3)));

            if ($userData && $userData->device_id) {
                $this->fcmService->sendNotification(
                    $userData->device_id,
                    "Reply Approved",
                    'Your private query has been approved with a reply.',
                    "Admin reply",
                    "Admin reply",
                    "2",
                    $request->query_id
                );
            }
        }

        return redirect()->to('/PrivateQuestionDetail/' . $request->query_id . '?flag=1');
    }

    public function declineReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        $validationError = ValidationHelper::handleValidationErrors($validator);
        if ($validationError !== null) {
            return $validationError;
        }

        $userQuery = UserAllQuery::where([
            'query_id' => $request->question_id,
            'mufti_id' => 9,
        ])->first();

        if ($userQuery) {
            $userQuery->status = 2;
            $userQuery->reason = $request->reason;
            $userQuery->save();

            $userData = User::find($userQuery->user_id);
            if ($userData && $userData->device_id) {
                $device_id = $userData->device_id;
                $title = "Reply Declined";
                $body = 'Your private query has been declined with a reason: ' . $request->reason;
                $messageType = "Admin reply";
                $otherData = "Admin reply";
                $notificationType = "3";

                $this->fcmService->sendNotification(
                    $device_id,
                    $title,
                    $body,
                    $messageType,
                    $otherData,
                    $notificationType,
                    $request->question_id
                );
            }
        } else {
            return response()->json(['error' => 'Query not found'], 404);
        }

        return redirect()->to('/PrivateQuestionDetail/' . $request->question_id . '?flag=1'); // flag=2 for declined
    }

    // public function editAdminReply(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'reply' => 'required|string', // Only validate the reply content
    //     ]);

    //     $validationError = ValidationHelper::handleValidationErrors($validator);
    //     if ($validationError !== null) {
    //         return $validationError;
    //     }

    //     $adminReply = AdminReply::find($request->reply_id);
    //     if (!$adminReply) {
    //         return redirect()->back()->withErrors(['error' => 'Reply not found.']);
    //     }

    //     $adminReply->reply = $request->reply;
    //     $adminReply->save();

    //     return redirect()->to('/PublicQuestionDetail/' . $adminReply->question_id . '?flag=1');
    // }

    public function deleteAdminReply(Request $request)
    {
        $request->validate([
            'reply_id' => 'required',
        ]);

        $adminReply = AdminReply::where([
            'id' => $request->reply_id,
            'question_type' => 'public', // Add the question_type check
        ])->first();

        if (!$adminReply) {
            return response()->json(['error' => 'Reply not found.'], 200);
        }

        $adminReply->delete();

        return response()->json(['success' => 'Reply deleted successfully.']);
    }
}
