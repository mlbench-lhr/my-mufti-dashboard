<?php

namespace App\Http\Controllers\Question;

use App\Helpers\ActivityHelper;
use App\Helpers\ResponseHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\Notification;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use App\Models\ScholarReply;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
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
        $user_id = $user->id;
        $message = $user->name . " posted a new question.";
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
        if ($questionVote) {
            return ResponseHelper::jsonResponse(false, 'Voted Already at this question');
        } else if ($voteQuestion) {
            $data = [
                'vote' => $request->vote,
            ];
            $voteQuestion->update($data);
            $userData = User::where('id', $question->user_id)->first();
            $device_id = $userData->device_id;
            $notifTitle = "Added Vote";
            $notiBody = 'User ' . $user->name . ' has vote on your question.';
            $message_type = "voting question";
            $other_data = "voting question";
            $notification_type = "2";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);
            return ResponseHelper::jsonResponse(true, 'Update Voted question successfully!');
        } else {
            $data = [
                'user_id' => $request->user_id,
                'question_id' => $request->question_id,
                'vote' => $request->vote,
            ];
            QuestionVote::create($data);

            $userData = User::where('id', $question->user_id)->first();
            $device_id = $userData->device_id;
            $notifTitle = "Added Vote";
            $notiBody = 'User ' . $user->name . ' has vote on your question.';
            $message_type = "voting question";
            $other_data = "voting question";
            $notification_type = "2";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);

            return ResponseHelper::jsonResponse(true, 'Voted question successfully!');
        }

    }

    public function all_question(Request $request)
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
        $perPage = 10;
        $totalPages = ceil(Question::all()->count() / $perPage);

        $questions = Question::forPage($page, $perPage)
            ->withCount([
                'votes as totalYesVote' => function ($query) {
                    $query->where('vote', 1);
                },
                'votes as totalNoVote' => function ($query) {
                    $query->where('vote', 2);
                },
                'comments as comments_count',
            ])
            ->with('user_detail')
            ->orderBy('created_at', 'DESC')
            ->get();

        $questions->each(function ($question) use ($request) {
            $currentUserVote = QuestionVote::where(['question_id' => $question->id, 'user_id' => $request->user_id])->first();

            if (!$currentUserVote) {
                $question->current_user_vote = 0;
            } else if ($currentUserVote->vote == 1) {
                $question->current_user_vote = 1;
            } else if ($currentUserVote->vote == 2) {
                $question->current_user_vote = 2;
            }
            $question->totalYesVote = (integer) $question->totalYesVote ?? (integer) 0;
            $question->totalNoVote = (integer) $question->totalNoVote ?? (integer) 0;
            $question->comments_count = (integer) $question->comments_count ?? (integer) 0;

        });

        $response = [
            'status' => true,
            'message' => 'All Public questions!',
            'totalpages' => $totalPages,
            'data' => $questions,
        ];
        return response()->json($response, 200);
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

        $response = [
            'status' => true,
            'message' => 'question detail!',
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

        $data = [
            'user_id' => $request->user_id,
            'question_id' => $request->question_id,
            'comment' => $request->comment,
        ];

        $userData = User::where('id', $question->user_id)->first();
        $device_id = $userData->device_id;
        $notifTitle = "Added Comment";
        $notiBody = 'User ' . $user->name . ' has comment on your question.';
        $message_type = "question comment";
        $other_data = "voting question";
        $notification_type = "2";

        $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);
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

        $user = User::where('id', $request->user_id)->first();

        if (!$user) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }
        $question = Question::where('id', $request->question_id)->first();

        if (!$question) {
            return ResponseHelper::jsonResponse(false, 'Question Not Found');
        }

        $data = [
            'user_id' => $request->user_id,
            'question_id' => $request->question_id,
            'reply' => $request->reply,
        ];
        ScholarReply::create($data);
        $userData = User::where('id', $question->user_id)->first();
        $device_id = $userData->device_id;
        $notifTitle = "Scholar Replied";
        $notiBody = $user->name . ' has reply on your question.';
        $message_type = "question reply";
        $other_data = "voting question";
        $notification_type = "2";

        $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);
        return ResponseHelper::jsonResponse(true, 'Reply added successfully!');
    }

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
            return ResponseHelper::jsonResponse(false, 'User Not Found');
        }
        $mufti = User::where(['id' => $request->mufti_id, 'mufti_status' => 2])->first();

        if (!$mufti) {
            return ResponseHelper::jsonResponse(false, 'Mufti Not Found');
        }
        $categories = Interest::where('user_id', $request->mufti_id)->pluck('interest')->toArray();
        $data = [
            'user_id' => $request->user_id,
            'question' => $request->question,
            'fiqa' => "General",
            'category' => $categories,
        ];

        $question = UserQuery::create($data);
        if ($request->mufti_id == 9) {
            $data2 = [
                'query_id' => $question->id,
                'user_id' => $request->user_id,
                'mufti_id' => 9,
                'question' => $request->question,
            ];
            $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();

            $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);
            $device_id = $user->device_id;
            $notifTitle = "Question Request Sent";

            $notiBody = 'Your request for private question to Mufti ' . $mufti->name . ' has been sent.';
            $body = 'Your request for private question to Mufti ' . $mufti->name . ' has been sent.';
            $message_type = "Question Request Sent";
            $other_data = "personal question";
            $notification_type = "1";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);

            $data = [
                'user_id' => $user->id,
                'title' => $notifTitle,
                'body' => $body,
            ];
            Notification::create($data);
            UserAllQuery::create($data2);
            return ResponseHelper::jsonResponse(true, 'Added question successfully!');

        } elseif ($request->mufti_id != 9 && $request->user_id != 9) {

            $data1 = [
                'query_id' => $question->id,
                'user_id' => $request->user_id,
                'mufti_id' => $request->mufti_id,
                'question' => $request->question,
            ];
            $data2 = [
                'query_id' => $question->id,
                'user_id' => $request->user_id,
                'mufti_id' => 9,
                'question' => $request->question,
            ];

            $this->send($mufti->device_id, "Asked Question", $user->name, $mufti->id);

            $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();

            $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);

            $device_id = $user->device_id;
            $notifTitle = "Question Request Sent";

            $notiBody = 'Your request for private question to Mufti ' . $mufti->name . ' has been sent.';
            $body = 'Your request for private question to Mufti ' . $mufti->name . ' has been sent.';
            $message_type = "Question Request Sent";
            $other_data = "personal question";
            $notification_type = "1";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);

            $data = [
                'user_id' => $user->id,
                'title' => $notifTitle,
                'body' => $body,
            ];
            Notification::create($data);

            UserAllQuery::create($data1);
            UserAllQuery::create($data2);

            return ResponseHelper::jsonResponse(true, 'Added question successfully!');
        } elseif ($request->mufti_id != 9 && $request->user_id == 9) {
            $data1 = [
                'query_id' => $question->id,
                'user_id' => $request->user_id,
                'mufti_id' => $request->mufti_id,
                'question' => $request->question,
            ];

            $this->send($mufti->device_id, "Asked Question", $user->name, $mufti->id);

            $device_id = $user->device_id;
            $notifTitle = "Question Request Sent";

            $notiBody = 'Your request for private question to Mufti ' . $mufti->name . ' has been sent.';
            $body = 'Your request for private question to Mufti ' . $mufti->name . ' has been sent.';
            $message_type = "Question Request Sent";
            $other_data = "personal question";
            $notification_type = "1";

            $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);

            $data = [
                'user_id' => $user->id,
                'title' => $notifTitle,
                'body' => $body,
            ];
            Notification::create($data);
            UserAllQuery::create($data1);
            return ResponseHelper::jsonResponse(true, 'Added question successfully!');
        }

    }
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

        $muftisUser = User::where('mufti_status', 2)->inRandomOrder()->limit(5)->pluck('id')->toArray();

        $valueToRemove = 9;

        $filteredArray = array_filter($muftisUser, function ($item) use ($valueToRemove) {
            return $item !== $valueToRemove;
        });

        $muftisUser = array_values($filteredArray);

        $muftis = Interest::whereIn('user_id', $muftisUser)->where(['fiqa' => $request->fiqa, 'interest' => $request->category])->distinct()->inRandomOrder()->limit(5)->pluck('user_id')->toArray();

        $data = [
            'user_id' => $request->user_id,
            'question' => $request->question,
            'fiqa' => $request->fiqa,
            'category' => (array) $request->category,
        ];

        $muftiDeviceId = User::whereIn('id', $muftisUser)->select('id', 'device_id')->get();

        $muftiDeviceId->each(function ($value) use ($user) {
            $this->send($value->device_id, "Asked Question", $user->name, $value->id);
        });

        $question = UserQuery::create($data);

        if (count($muftis) <= 0) {
            if ($request->user_id == 9) {
                return ResponseHelper::jsonResponse(true, 'Added question successfully!');
            }

            $data2 = [
                'query_id' => $question->id,
                'user_id' => $request->user_id,
                'mufti_id' => 9,
                'question' => $request->question,
            ];

            $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();
            $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);

            UserAllQuery::create($data2);

            return ResponseHelper::jsonResponse(true, 'Added question successfully!');

        }

        if ($request->user_id == 9) {

            $questionId = $question->id;
            collect($muftis)->map(function ($value) use ($request, $questionId) {
                return [
                    'query_id' => $questionId,
                    'user_id' => $request->user_id,
                    'mufti_id' => $value,
                    'question' => $request->question,
                ];
            })->each(function ($data) {
                UserAllQuery::create($data);
            });

            return ResponseHelper::jsonResponse(true, 'Added question successfully!');

        } else {
            $defaultMufti = User::where(['id' => 9, 'mufti_status' => 2])->first();

            $this->send($defaultMufti->device_id, "Asked Question", $user->name, $defaultMufti->id);

            $data2 = [
                'query_id' => $question->id,
                'user_id' => $request->user_id,
                'mufti_id' => 9,
                'question' => $request->question,
            ];
            UserAllQuery::create($data2);

            $questionId = $question->id;
            collect($muftis)->map(function ($value) use ($request, $questionId) {
                return [
                    'query_id' => $questionId,
                    'user_id' => $request->user_id,
                    'mufti_id' => $value,
                    'question' => $request->question,
                ];
            })->each(function ($data) {
                UserAllQuery::create($data);
            });

            return ResponseHelper::jsonResponse(true, 'Added question successfully!');
        }

    }

    // send notification
    public function send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type)
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
            'other_data' => $other_data,
            'message_Type' => $message_type,
            'notification_type' => $notification_type,
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

    public function send($deviceId, $title, $name, $muftiId)
    {
        $device_id = $deviceId;
        $notifTitle = $title;
        $notiBody = 'User' . ' ' . $name . ' wants to ask a question for you.';
        $body = 'User' . ' ' . $name . ' wants to ask a question for you.';
        $message_type = $title;
        $other_data = "personal question";
        $notification_type = "1";

        $this->send_notification($device_id, $notifTitle, $notiBody, $message_type, $other_data, $notification_type);

        $data = [
            'user_id' => $muftiId,
            'title' => $notifTitle,
            'body' => $body,
        ];
        Notification::create($data);
    }
}
