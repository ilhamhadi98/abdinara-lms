<?php

namespace App\Livewire;

use App\Models\Tryout;
use App\Models\TryoutSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TryoutList extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    #[Url(history: true)]
    public string $search = '';

    public ?int $jumpPage = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function jumpToPage(): void
    {
        if ($this->jumpPage !== null && $this->jumpPage >= 1) {
            $this->setPage((int) $this->jumpPage);
            $this->jumpPage = null;
        }
    }

    public function startTryout(int $tryoutId): void
    {
        $tryout = Tryout::where('id', $tryoutId)->where('is_active', true)->firstOrFail();
        $user   = Auth::user();

        // Check if there's an ongoing session that hasn't expired
        $existing = TryoutSession::where('user_id', $user->id)
            ->where('tryout_id', $tryout->id)
            ->where('status', 'ongoing')
            ->first();

        if ($existing && !$existing->isExpired()) {
            $this->redirect(route('tryout.engine', $existing->id));
            return;
        }

        // If expired, mark as finished
        if ($existing && $existing->isExpired()) {
            $existing->update(['status' => 'finished', 'finished_at' => now()]);
        }

        // Create new session
        $session = TryoutSession::create([
            'user_id'    => $user->id,
            'tryout_id'  => $tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes($tryout->duration_minutes),
            'status'     => 'ongoing',
        ]);

        $this->redirect(route('tryout.engine', $session->id));
    }

    public function render()
    {
        $query = Tryout::where('is_active', true)
            ->withCount('questions');

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                  ->orWhereHas('category', function ($cq) use ($term) {
                      $cq->where('name', 'like', $term);
                  })
                  ->orWhereHas('subtopic', function ($sq) use ($term) {
                      $sq->where('name', 'like', $term);
                  });
            });
        }

        // Urutan ASC (Bukan DESC)
        $tryouts = $query->orderBy('id', 'asc')
            ->paginate(12);

        // Get user's ongoing/finished sessions for this page of tryouts
        $userId     = Auth::id();
        $tryoutIds  = $tryouts->pluck('id');
        $mySessions = TryoutSession::where('user_id', $userId)
            ->whereIn('tryout_id', $tryoutIds)
            ->latest()
            ->get(['id', 'tryout_id', 'status', 'score', 'finished_at', 'expired_at'])
            ->keyBy('tryout_id');

        return view('livewire.tryout-list', [
            'tryouts'    => $tryouts,
            'mySessions' => $mySessions,
            'search'     => $this->search,
            'jumpPage'   => $this->jumpPage,
        ])->layout('layouts.app');
    }
}

