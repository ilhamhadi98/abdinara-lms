<?php

namespace App\Http\Controllers;

use App\Models\TryoutAnswer;
use App\Models\TryoutSession;
use Illuminate\Support\Facades\Auth;

class TryoutSessionController extends Controller
{
    /**
     * Show the detailed result of a finished session.
     * Only the session owner can view.
     */
    public function show(TryoutSession $session)
    {
        abort_if($session->user_id !== Auth::id(), 403);
        abort_if($session->status !== 'finished', 404);

        $session->load('tryout:id,title,total_questions,duration_minutes');

        // Fetch all questions associated with this tryout via the pivot table
        // and left join with user's answers for this specific session.
        $answers = \DB::table('tryout_questions as tq')
            ->join('questions as q', 'tq.question_id', '=', 'q.id')
            ->leftJoin('tryout_answers as ta', function ($join) use ($session) {
                $join->on('q.id', '=', 'ta.question_id')
                     ->where('ta.session_id', '=', $session->id);
            })
            ->where('tq.tryout_id', $session->tryout_id)
            ->select(
                'q.id as question_id',
                'q.question_text',
                'q.image',
                'q.correct_answer',
                'q.explanation',
                'q.option_a',
                'q.option_b',
                'q.option_c',
                'q.option_d',
                'q.option_e',
                'ta.selected_answer'
            )
            ->orderBy('tq.sort_order')
            ->get()
            ->map(function ($row) {
                // Convert to a generic object to match the view's expectations
                $a = new \stdClass();
                $a->question_id = $row->question_id;
                $a->selected_answer = $row->selected_answer;
                $a->is_correct = $row->selected_answer && $row->selected_answer === $row->correct_answer;
                
                // Mocking the question relationship for the view
                $a->question = new \stdClass();
                $a->question->question_text = $row->question_text;
                $a->question->image = $row->image;
                $a->question->correct_answer = $row->correct_answer;
                $a->question->explanation = $row->explanation;
                $a->question->option_a = $row->option_a;
                $a->question->option_b = $row->option_b;
                $a->question->option_c = $row->option_c;
                $a->question->option_d = $row->option_d;
                $a->question->option_e = $row->option_e;
                
                return $a;
            });

        return view('tryout.result-show', compact('session', 'answers'));
    }
}
