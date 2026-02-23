<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tryout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TryoutController extends Controller
{
    public function index()
    {
        $tryouts = Tryout::withCount('questions', 'sessions')
            ->latest()
            ->paginate(15);

        return view('admin.tryouts.index', compact('tryouts'));
    }

    public function create()
    {
        $questionCount = Question::count();
        return view('admin.tryouts.create', compact('questionCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:200',
            'duration_minutes' => 'required|integer|min:10|max:300',
            'total_questions'  => 'required|integer|min:1|max:200',
        ]);

        $totalAvailable = Question::count();

        if ($totalAvailable < $data['total_questions']) {
            return back()->withErrors([
                'total_questions' => "Hanya tersedia {$totalAvailable} soal di database.",
            ])->withInput();
        }

        // Generate random question IDs via SQL (no PHP loop)
        $questionIds = Question::select('id')
            ->inRandomOrder()
            ->limit($data['total_questions'])
            ->pluck('id');

        $tryout = Tryout::create($data);

        // Bulk insert pivot with sort_order
        $pivotData = $questionIds->values()->map(fn($id, $index) => [
            'tryout_id'   => $tryout->id,
            'question_id' => $id,
            'sort_order'  => $index + 1,
        ])->toArray();

        DB::table('tryout_questions')->insert($pivotData);

        return redirect()->route('admin.tryouts.index')
                         ->with('success', "Tryout \"{$tryout->title}\" berhasil dibuat dengan {$questionIds->count()} soal.");
    }

    public function togglePublish(Tryout $tryout)
    {
        $tryout->update(['is_active' => !$tryout->is_active]);

        $status = $tryout->is_active ? 'dipublikasikan' : 'dinonaktifkan';
        return back()->with('success', "Tryout berhasil {$status}.");
    }

    public function destroy(Tryout $tryout)
    {
        $tryout->delete();
        return back()->with('success', 'Tryout berhasil dihapus.');
    }
}
