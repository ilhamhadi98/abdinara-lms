<?php

namespace Tests\Feature;

use App\Livewire\TryoutEngine;
use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use App\Models\Tryout;
use App\Models\TryoutSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TryoutEngineAndScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_tryout_engine_can_submit_and_score_correctly()
    {
        // 1. Setup the basic Models for Tryout
        $user = User::factory()->create();
        
        $category = Category::create(['name' => 'Test Category']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Math']);

        $tryout = Tryout::create([
            'title' => 'Simulasi UTBK Akhir Tahun',
            'duration_minutes' => 90,
            'total_questions' => 3,
            'is_active' => true,
        ]);

        // Create 3 questions with specific correct answers
        $q1 = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Berapa 1 + 1?',
            'option_a' => '1', 'option_b' => '2', 'option_c' => '3', 'option_d' => '4', 'option_e' => '5',
            'correct_answer' => 'B',
            'difficulty' => 1,
        ]);

        $q2 = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Berapa 2 x 3?',
            'option_a' => '6', 'option_b' => '2', 'option_c' => '3', 'option_d' => '4', 'option_e' => '5',
            'correct_answer' => 'A',
            'difficulty' => 1,
        ]);

        $q3 = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Ibukota Indonesia?',
            'option_a' => 'Bandung', 'option_b' => 'Surabaya', 'option_c' => 'Jakarta', 'option_d' => 'Medan', 'option_e' => 'Bali',
            'correct_answer' => 'C',
            'difficulty' => 1,
        ]);

        // Attach questions to the Tryout inside the pivot table
        $tryout->questions()->attach([
            $q1->id => ['sort_order' => 1],
            $q2->id => ['sort_order' => 2],
            $q3->id => ['sort_order' => 3],
        ]);

        // 2. Simulate Tryout Session initialization like TryoutList does
        $session = TryoutSession::create([
            'user_id'    => $user->id,
            'tryout_id'  => $tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes($tryout->duration_minutes),
            'status'     => 'ongoing',
        ]);

        // 3. Test the TryoutEngine Livewire Component Logic
        // Simulate a user answering 2 correctly and 1 wrong.
        // Q1 (Correct: B) -> We answer 'B'
        // Q2 (Correct: A) -> We answer 'A'
        // Q3 (Correct: C) -> We answer 'E' (Wrong)
        // Expected Score: 2

        Livewire::actingAs($user)
            ->test(TryoutEngine::class, ['session' => $session])
            ->set('currentIndex', 0) // Looking at Q1
            ->call('selectAnswer', 'B')
            ->call('nextQuestion')
            
            ->assertSet('currentIndex', 1) // Now At Q2
            ->call('selectAnswer', 'A')
            ->call('nextQuestion')
            
            ->assertSet('currentIndex', 2) // Now At Q3
            ->call('selectAnswer', 'E')
            
            // Tryout Complete, let's submit
            ->call('submitTryout')
            ->assertRedirect(route('tryout.results.show', $session->id));

        // 4. Assert Backend Logic/Scoring correctness
        // Refresh session from database to see updated states
        $session->refresh();

        $this->assertEquals('finished', $session->status, 'Sistem tidak mengubah status menjadi finished.');
        $this->assertEquals(2, $session->score, 'Perhitungan skor final tidak akurat. Seharusnya berpoin 2.');

        // Verify TryoutAnswers stored in DB
        $this->assertDatabaseHas('tryout_answers', [
            'session_id' => $session->id,
            'question_id' => $q1->id,
            'selected_answer' => 'B'
        ]);

        $this->assertDatabaseHas('tryout_answers', [
            'session_id' => $session->id,
            'question_id' => $q3->id,
            'selected_answer' => 'E'
        ]);
    }
}
