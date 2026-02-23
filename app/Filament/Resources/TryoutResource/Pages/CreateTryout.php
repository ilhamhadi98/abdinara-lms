<?php

namespace App\Filament\Resources\TryoutResource\Pages;

use App\Filament\Resources\TryoutResource;
use App\Models\Question;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateTryout extends CreateRecord
{
    protected static string $resource = TryoutResource::class;

    // Auto-assign random questions setelah tryout dibuat
    protected function afterCreate(): void
    {
        $tryout = $this->record;
        $total  = $tryout->total_questions;

        $questionIds = Question::inRandomOrder()
            ->limit($total)
            ->pluck('id');

        if ($questionIds->isEmpty()) {
            return;
        }

        $rows = $questionIds->values()->map(fn ($id, $i) => [
            'tryout_id'   => $tryout->id,
            'question_id' => $id,
            'sort_order'  => $i + 1,
        ])->toArray();

        DB::table('tryout_questions')->insert($rows);
    }
}
