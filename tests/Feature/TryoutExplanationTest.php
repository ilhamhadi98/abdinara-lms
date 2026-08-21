<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use App\Models\TryoutSession;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TryoutExplanationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_view_question_explanation_on_finished_result_page(): void
    {
        $user = User::factory()->create([
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $user->assignRole('member');

        $category = Category::create(['name' => 'TWK']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Nasionalisme']);

        $tryout = Tryout::create([
            'title' => 'Tryout Simulasi CAT SKD',
            'duration_minutes' => 100,
            'total_questions' => 1,
            'is_active' => true,
        ]);

        $explanationText = 'Ini adalah pembahasan lengkap dan mendalam mengenai nilai persatuan NKRI.';

        $question = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Buktinya nilai persatuan digali dari budaya bangsa adalah...',
            'option_a' => 'Pilihan A',
            'option_b' => 'Pilihan B',
            'option_c' => 'Pilihan C',
            'option_d' => 'Pilihan D',
            'option_e' => 'Kuatnya rasa persatuan memperjuangkan kemerdekaan',
            'correct_answer' => 'E',
            'explanation' => $explanationText,
            'difficulty' => 1,
        ]);

        $tryout->questions()->attach($question->id, ['sort_order' => 1]);

        $session = TryoutSession::create([
            'user_id' => $user->id,
            'tryout_id' => $tryout->id,
            'started_at' => now()->subMinutes(30),
            'expired_at' => now()->addMinutes(70),
            'finished_at' => now(),
            'status' => 'finished',
            'score' => 0,
        ]);

        TryoutAnswer::create([
            'session_id' => $session->id,
            'question_id' => $question->id,
            'selected_answer' => 'C',
        ]);

        $response = $this->actingAs($user)->get(route('tryout.results.show', $session->id));

        $response->assertStatus(200);
        $response->assertSee('Pembahasan Lengkap:');
        $response->assertSee($explanationText);
        $response->assertSee('x-data="{ open: false }"', false);
        $response->assertSee('@click="open = !open"', false);
        $response->assertSee('x-show="open"', false);
        $response->assertDontSee('id="explanation-0"', false);
    }

    public function test_question_without_explanation_does_not_render_explanation_button(): void
    {
        $user = User::factory()->create([
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $user->assignRole('member');

        $category = Category::create(['name' => 'TIU']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Logika']);

        $tryout = Tryout::create([
            'title' => 'Tryout Tanpa Pembahasan',
            'duration_minutes' => 50,
            'total_questions' => 1,
            'is_active' => true,
        ]);

        $question = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Soal tanpa teks pembahasan',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'A',
            'explanation' => null,
            'difficulty' => 1,
        ]);

        $tryout->questions()->attach($question->id, ['sort_order' => 1]);

        $session = TryoutSession::create([
            'user_id' => $user->id,
            'tryout_id' => $tryout->id,
            'started_at' => now()->subMinutes(10),
            'expired_at' => now()->addMinutes(40),
            'finished_at' => now(),
            'status' => 'finished',
            'score' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('tryout.results.show', $session->id));

        $response->assertStatus(200);
        $response->assertDontSee('Pembahasan Lengkap:');
        $response->assertDontSee('Lihat Pembahasan');
    }

    public function test_user_cannot_view_another_users_session_result(): void
    {
        $owner = User::factory()->create(['subscription_expires_at' => now()->addMonth()]);
        $otherUser = User::factory()->create(['subscription_expires_at' => now()->addMonth()]);

        $tryout = Tryout::create([
            'title' => 'Tryout Private',
            'duration_minutes' => 60,
            'total_questions' => 0,
            'is_active' => true,
        ]);

        $session = TryoutSession::create([
            'user_id' => $owner->id,
            'tryout_id' => $tryout->id,
            'started_at' => now()->subMinutes(10),
            'expired_at' => now()->addMinutes(50),
            'finished_at' => now(),
            'status' => 'finished',
            'score' => 0,
        ]);

        $response = $this->actingAs($otherUser)->get(route('tryout.results.show', $session->id));
        $response->assertStatus(403);
    }
}