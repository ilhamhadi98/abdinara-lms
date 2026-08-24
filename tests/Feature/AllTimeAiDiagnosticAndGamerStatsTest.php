<?php

namespace Tests\Feature;

use App\Models\Battle;
use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\Tryout;
use App\Models\TryoutAnswer;
use App\Models\TryoutSession;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllTimeAiDiagnosticAndGamerStatsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_view_all_time_gamer_stats_radar_and_strengths_weaknesses(): void
    {
        $user = User::factory()->create([
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $user->assignRole('member');

        $category = Category::create(['name' => 'TWK']);
        $subtopic1 = Subtopic::create(['category_id' => $category->id, 'name' => 'Nasionalisme']);
        $subtopic2 = Subtopic::create(['category_id' => $category->id, 'name' => 'Integritas']);

        $q1 = Question::create([
            'subtopic_id' => $subtopic1->id,
            'question_text' => 'Soal 1 TWK',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'A',
            'explanation' => 'Penjelasan',
            'difficulty' => 1,
        ]);

        $q2 = Question::create([
            'subtopic_id' => $subtopic2->id,
            'question_text' => 'Soal 2 TWK',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'B',
            'explanation' => 'Penjelasan 2',
            'difficulty' => 1,
        ]);

        $tryout = Tryout::create([
            'title' => 'Simulasi Tryout Perdana',
            'duration_minutes' => 60,
            'total_questions' => 2,
            'is_active' => true,
        ]);
        $tryout->questions()->attach([$q1->id => ['sort_order' => 1], $q2->id => ['sort_order' => 2]]);

        $session = TryoutSession::create([
            'user_id' => $user->id,
            'tryout_id' => $tryout->id,
            'started_at' => now()->subHour(),
            'finished_at' => now(),
            'duration_seconds' => 1800,
            'score' => 100,
            'status' => 'finished',
        ]);

        // User answers Q1 correctly, Q2 wrong
        TryoutAnswer::create([
            'session_id' => $session->id,
            'question_id' => $q1->id,
            'selected_answer' => 'A',
        ]);
        TryoutAnswer::create([
            'session_id' => $session->id,
            'question_id' => $q2->id,
            'selected_answer' => 'C',
        ]);

        // Create Battle Stats
        Battle::create([
            'room_code' => 'BTL123',
            'player1_id' => $user->id,
            'player2_name' => 'AI Bot',
            'is_bot' => true,
            'status' => 'finished',
            'winner_id' => $user->id,
            'winner_name' => $user->name,
            'player1_score' => 300,
            'player2_score' => 100,
        ]);

        // Create Tournament Stats
        $t = Tournament::create([
            'title' => 'Liga Nasional',
            'edition_number' => 1,
            'tryout_id' => $tryout->id,
            'start_at' => now()->subDays(2),
            'end_at' => now()->subDay(),
            'is_active' => false,
        ]);
        TournamentParticipant::create([
            'tournament_id' => $t->id,
            'user_id' => $user->id,
            'score' => 100,
            'duration_seconds' => 1800,
            'rank_position' => 3,
            'is_passed' => true,
        ]);

        $response = $this->actingAs($user)->get(route('tryout.results'));

        $response->assertStatus(200);

        // Gamer Hero & Level
        $response->assertSee('Statistik');
        $response->assertSee('LEVEL');
        $response->assertSee('EXP Pejuang:');
        $response->assertSee('Win Rate Duel 1v1');
        $response->assertSee('100%'); // 1 win out of 1 battle

        // Achievement Badges
        $response->assertSee('Lencana');
        $response->assertSee('First Step');
        $response->assertSee('Duel Warrior');

        // All-Time Radar & Diagnostics
        $response->assertSee('allTimeRadarChart');
        $response->assertSee('Kelebihan (Materi Terkuat)');
        $response->assertSee('Nasionalisme'); // 100% accuracy
        $response->assertSee('Kekurangan (Harus Diperbaiki)');
        $response->assertSee('Integritas'); // 0% accuracy
        $response->assertSee('Rekomendasi Belajar Personal Hari Ini');

        // Session History
        $response->assertSee('Simulasi Tryout Perdana');
        $response->assertSee('Riwayat Tryout Individual');
    }
}