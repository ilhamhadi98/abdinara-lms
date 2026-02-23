<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $questions = Question::with('subtopic.category')
            ->when($request->category_id, fn($q) => $q->whereHas('subtopic', fn($s) => $s->where('category_id', $request->category_id)))
            ->when($request->subtopic_id, fn($q) => $q->where('subtopic_id', $request->subtopic_id))
            ->when($request->difficulty, fn($q) => $q->where('difficulty', $request->difficulty))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $subtopics  = $request->category_id
            ? Subtopic::where('category_id', $request->category_id)->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('admin.questions.index', compact('questions', 'categories', 'subtopics'));
    }

    public function create()
    {
        $categories = Category::with('subtopics')->orderBy('name')->get(['id', 'name']);
        return view('admin.questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subtopic_id'   => 'required|exists:subtopics,id',
            'question_text' => 'required|string',
            'option_a'      => 'required|string|max:500',
            'option_b'      => 'required|string|max:500',
            'option_c'      => 'required|string|max:500',
            'option_d'      => 'required|string|max:500',
            'option_e'      => 'required|string|max:500',
            'correct_answer'=> 'required|in:A,B,C,D,E',
            'difficulty'    => 'required|in:1,2,3',
        ]);

        Question::create($data);

        return redirect()->route('admin.questions.index')
                         ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}
