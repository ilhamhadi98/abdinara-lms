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

        $session->load('tryout:id,title,total_questions,duration_minutes', 'user:id,name,email');

        // Fetch all questions associated with this tryout via the pivot table
        // along with their subtopic & category, and user answers
        $rawAnswers = \DB::table('tryout_questions as tq')
            ->join('questions as q', 'tq.question_id', '=', 'q.id')
            ->leftJoin('subtopics as s', 'q.subtopic_id', '=', 's.id')
            ->leftJoin('categories as c', 's.category_id', '=', 'c.id')
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
                's.name as subtopic_name',
                'c.name as category_name',
                'ta.selected_answer'
            )
            ->orderBy('tq.sort_order')
            ->get();

        $subtopicStats = [];

        $answers = $rawAnswers->map(function ($row) use (&$subtopicStats) {
            $isCorrect = $row->selected_answer && $row->selected_answer === $row->correct_answer;
            $subName = $row->subtopic_name ?? ($row->category_name ?? 'Umum');

            if (!isset($subtopicStats[$subName])) {
                $subtopicStats[$subName] = [
                    'name' => $subName,
                    'category' => $row->category_name ?? 'SKD',
                    'total' => 0,
                    'correct' => 0,
                ];
            }
            $subtopicStats[$subName]['total']++;
            if ($isCorrect) {
                $subtopicStats[$subName]['correct']++;
            }

            // Convert to a generic object to match the view's expectations
            $a = new \stdClass();
            $a->question_id = $row->question_id;
            $a->selected_answer = $row->selected_answer;
            $a->is_correct = $isCorrect;
            $a->subtopic_name = $row->subtopic_name;
            $a->category_name = $row->category_name;
            
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

        // Calculate accuracy per subtopic for Radar Chart & Diagnostic
        $radarLabels = [];
        $radarData = [];
        $analysisList = [];

        foreach ($subtopicStats as $sub) {
            $accuracy = $sub['total'] > 0 ? round(($sub['correct'] / $sub['total']) * 100) : 0;
            $radarLabels[] = $sub['name'];
            $radarData[] = $accuracy;
            $analysisList[] = [
                'name' => $sub['name'],
                'category' => $sub['category'],
                'total' => $sub['total'],
                'correct' => $sub['correct'],
                'accuracy' => $accuracy,
            ];
        }

        // Top Strengths & Weaknesses
        usort($analysisList, fn($a, $b) => $b['accuracy'] <=> $a['accuracy']);
        $strengths = array_slice($analysisList, 0, 2);
        
        $weaknessesList = array_reverse($analysisList);
        $weaknesses = array_slice($weaknessesList, 0, 2);

        // Calculate passing probability
        $totalQ = max(1, count($answers));
        $correctCount = collect($answers)->where('is_correct', true)->count();
        $overallAcc = round(($correctCount / $totalQ) * 100);
        $passingProbability = min(99, max(15, round($overallAcc * 1.15)));

        return view('tryout.result-show', compact(
            'session',
            'answers',
            'radarLabels',
            'radarData',
            'strengths',
            'weaknesses',
            'passingProbability'
        ));
    }
}
