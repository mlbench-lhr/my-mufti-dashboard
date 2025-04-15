<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FAQ;
use Carbon\Carbon;
use App\Services\FcmService;
use Illuminate\Support\Facades\Validator;

class FAQController extends Controller
{
    
    // Show all FAQs
    public function all_faq()
    {
        $faqs = FAQ::orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.AllFAQ', compact('faqs'));
    }
    public function get_all_faq(Request $request)
{
    $searchTerm = $request->input('search');
    $faqCount = FAQ::count();
    $query = FAQ::query();

    if ($searchTerm) {
        $query->where('question', 'LIKE', '%' . $searchTerm . '%');
    }

    $query->orderBy('created_at', 'DESC');
    $faqs = $query->paginate(10);

    return response()->json([
        'faqCount' => $faqCount,
        'faqs' => $faqs // formatted_date will be automatically included
    ]);
}


    // Store a new FAQ
    public function submit_FAQ(Request $request)
{

    $data = [
        'question' => $request->question,
        'answer' => $request->answer,
    ];

    FAQ::create($data);

    return response()->json(['success' => true, 'message' => 'FAQ submitted successfully!']);
}
 public function faq_detail(Request $request){
    $faq_id=$request->id;
    $faq = FAQ::where('id',$request->id)->first();
    
    return view('frontend.AllFAQDetail', compact('faq'));
 }
 public function get_faq($id)
    {
        $faq = FAQ::findOrFail($id);
        return response()->json($faq);
    }
 public function edit_faq(Request $request)
    {
        $request->validate([
           'question' => 'required',
           'answer' => 'required',
        ]);

        $faq = FAQ::findOrFail($request->id);
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json(['success' => true, 'message' => 'FAQ updated successfully!']);
    }
    public function delete_faq(Request $request,$id)
{
    $faq = FAQ::find($id);

    if (!$faq) {
        return redirect()->back()->with('error', 'FAQ not found.');
    }

    $faq->delete();

    return redirect()->route('AllFAQs')->with('success', 'FAQ deleted successfully.');
}

public function getEventDetail(Request $request, $event_id)
{
    $page  = $request->get('page', 1);   // only page from URL
    $limit = 10; // fixed number of items per page

    $event = Event::with('scholars', 'hosted_by.interests')
        ->where('id', $event_id)
        ->first();

    if (! $event) {
        return ResponseHelper::jsonResponse(false, 'Event Not Found');
    }

    // Format event categories
    $event->event_category = collect($event->event_category)->values()->all();

    // Add question category counts
    $event->question_category = collect($event->question_category)->mapWithKeys(function ($value) use ($event_id) {
        $count = EventQuestion::where([
            'event_id' => $event_id,
            'category' => $value
        ])->count();
        return [$value => $count];
    });

    // Get paginated event questions
    $questionsQuery = EventQuestion::where('event_id', $event_id);
    $paginatedQuestions = $questionsQuery->paginate($limit, ['*'], 'page', $page);

    // Replace event_questions with paginated data
    $event->event_questions = $paginatedQuestions->items();

    // Response
    return response()->json([
        'status'      => true,
        'message'     => 'Event detail!',
        'per_page'    => $limit,
        'total_pages' => $paginatedQuestions->lastPage(),
        'page'        => $paginatedQuestions->currentPage(),
        'data'        => $event,
    ], 200);
}



}
