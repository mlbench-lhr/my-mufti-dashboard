<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use App\Models\ScholarReply;
use App\Models\User;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use App\Models\ReportQuestion;
use App\Models\Mufti;




class QuestionsController extends Controller
{
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
        $query->orderBy('created_at', 'DESC');
        if ($searchTerm) {
            $query->where('question', 'LIKE', '%' . $searchTerm . '%');
        }
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
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

        // dd($question);
        return view('frontend.PublicQuestionDetail', compact('question', 'question_id', 'type', 'user_id'));
    }

    public function get_question_comments(Request $request)
    {
        $searchTerm = $request->input('search');

        $userCount = QuestionComment::with('user')->where('question_id', $request->id)->count();
        $query = QuestionComment::with('user')->where('question_id', $request->id);

        $user = $query->paginate(3);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
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
        // delete question comments
        $comments = QuestionComment::where('question_id', $id)->delete();
        // delete question votes
        $vote = QuestionVote::where('question_id', $id)->delete();
        // delete scholars reply
        $scholar_reply = ScholarReply::where('question_id', $id)->delete();
        ReportQuestion::where('question_id', $id)->delete();

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
            $row->registration_date = $row->created_at->format('j \\ F Y');
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
        $reportedQuestions = ReportQuestion::with('user_detail', 'question.user_detail')
            ->get();

        return view('frontend.AllReportedQuestions', compact('reportedQuestions'));
    }

    public function get_all_reported_questions(Request $request)
    {
        $searchTerm = $request->input('search');

        $userCount = ReportQuestion::count('question_id');

        $query = ReportQuestion::with('user_detail', 'question.user_detail')
            ->orderBy('created_at', 'desc');

        if ($searchTerm) {
            $query->whereHas('question', function ($subQuery) use ($searchTerm) {
                $subQuery->where('question', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $reportedQuestions = $query->paginate(10);

        if ($reportedQuestions->isEmpty()) {
            return response()->json(['message' => 'No reported questions found', 'reportedQuestions' => []]);
        }

        foreach ($reportedQuestions as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
        }

        return response()->json(['userCount' => $userCount, 'reportedQuestions' => $reportedQuestions]);
    }
}
