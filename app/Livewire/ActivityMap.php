<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivityMap extends Component
{
    public function render()
    {
        $userId = Auth::id();
        $startDate = today()->subDays(364);
        
        // Pastikan grid selaras dengan hari Senin agar tidak terpotong (7 hari x N kolom)
        while ($startDate->dayOfWeekIso !== 1) {
            $startDate->subDay();
        }

        $endDate = today();

        $activities = UserActivity::where('user_id', $userId)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->selectRaw('date, sum(count) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $days = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->toDateString();
            $count = $activities[$dateStr] ?? 0;
            
            $level = 0;
            if ($count > 0 && $count <= 2) {
                $level = 1;
            } elseif ($count > 2 && $count <= 5) {
                $level = 2;
            } elseif ($count > 5 && $count <= 8) {
                $level = 3;
            } elseif ($count > 8) {
                $level = 4;
            }

            $days[] = [
                'date' => $dateStr,
                'count' => $count,
                'level' => $level,
                'label' => $currentDate->translatedFormat('d M Y')
            ];
            
            $currentDate->addDay();
        }

        return view('livewire.activity-map', compact('days'));
    }
}
