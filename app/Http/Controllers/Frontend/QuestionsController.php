<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\{Question, User, QuestionComment, QuestionVote, ScholarReply, UserQuery, UserAllQuery};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class QuestionsController extends Controller
{
    public  function all_public_questions()
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
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }

    public  function public_question_detail(Request $request)
    {
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
        return view('frontend.PublicQuestionDetail', compact('question', 'question_id'));
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



    public  function all_private_questions()
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
        $user = $query->paginate(10);
        foreach ($user as $row) {
            $row->registration_date = $row->created_at->format('j \\ F Y');
        }
        return response()->json(['userCount' => $userCount, 'users' => $user]);
    }


    public  function private_question_detail(Request $request)
    {
        $question_id = $request->id;
        $detail = UserQuery::with('questioned_by')->where('id', $question_id)->first();
        $question_from = UserAllQuery::with('mufti_detail.interests')->where('query_id', $question_id)->get();
        return view('frontend.PrivateQuestionDetail', compact('detail', 'question_id', 'question_from'));
    }
}
