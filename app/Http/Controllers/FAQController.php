<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;

class FAQController extends Controller
{
    // Show all FAQs
    public function index()
    {
        $faqs = Faq::orderBy('created_at', 'desc')->paginate(10);
        return view('allfaqs', compact('faqs'));
    }

    // Store a new FAQ
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return redirect()->back()->with('success', 'FAQ added successfully!');
    }
}
