<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserTarget;
use Illuminate\Support\Facades\Auth;

class UserTargetWidget extends Component
{
    public $target_type = 'tryout';
    public $target_value = 5;

    public function mount()
    {
        $target = UserTarget::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->first();
        
        if ($target) {
            $this->target_type = $target->target_type;
            $this->target_value = $target->target_value;
        }
    }

    public function setTarget()
    {
        UserTarget::updateOrCreate(
            ['user_id' => Auth::id(), 'is_completed' => false],
            [
                'target_type' => $this->target_type,
                'target_value' => $this->target_value,
                'current_value' => 0,
                'deadline_date' => now()->addDays(7),
            ]
        );

        $this->dispatch('targetUpdated');
        return redirect()->to('/dashboard');
    }

    public function render()
    {
        $currentTarget = UserTarget::where('user_id', Auth::id())
            ->where('is_completed', false)
            ->first();

        return view('livewire.user-target-widget', [
            'currentTarget' => $currentTarget
        ]);
    }
}
