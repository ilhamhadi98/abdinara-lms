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

        $query = Question::query();

        // Filter berdasarkan Subtopik (Prioritas Utama)
        if ($tryout->subtopic_id) {
            $query->where('subtopic_id', $tryout->subtopic_id);
        } 
        // Filter berdasarkan Kategori (Jika Subtopik tidak dipilih)
        elseif ($tryout->category_id) {
            $query->whereHas('subtopic', function ($q) use ($tryout) {
                $q->where('category_id', $tryout->category_id);
            });
        }

        // Filter berdasarkan Tingkat Kesulitan
        if ($tryout->difficulty) {
            $query->where('difficulty', $tryout->difficulty);
        }

        $questionIds = $query->inRandomOrder()
            ->limit($total)
            ->pluck('id');

        if ($questionIds->isEmpty()) {
            // Optional: Tambahkan notifikasi jika tidak ada soal yang sesuai
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
