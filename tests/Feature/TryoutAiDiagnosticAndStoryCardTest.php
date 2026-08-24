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

class TryoutAiDiagnosticAndStoryCardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_sees_ai_diagnostic_radar_chart_and_story_card_on_finished_result_page(): void
    {
        $user = User::factory()->create([
            'subscription_expires_at' => now()->addMonth(),
        ]);
        $user->assignRole('member');

        $category = Category::create(['name' => 'TWK']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Nasionalisme']);

        $q = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Soal Pancasila kebangsaan?',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
            'correct_answer' => 'A',
            'explanation' => 'Pembahasan nasionalisme.',
            'difficulty' => 1,
        ]);

        $tryout = Tryout::create([
            'title' => 'Simulasi SKD Edisi AI',
            'duration_minutes' => 60,
            'total_questions' => 1,
            'is_active' => true,
        ]);

        $tryout->questions()->attach($q->id, ['sort_order' => 1]);

        $session = TryoutSession::create([
            'user_id' => $user->id,
            'tryout_id' => $tryout->id,
            'started_at' => now()->subMinutes(20),
            'finished_at' => now(),
            'duration_seconds' => 1200,
            'score' => 1,
            'status' => 'finished',
        ]);

        TryoutAnswer::create([
            'session_id' => $session->id,
            'question_id' => $q->id,
            'selected_answer' => 'A',
        ]);

        $package = \App\Models\SubscriptionPackage::create([
            'name' => 'Gold',
            'duration_days' => 90,
            'price' => 75000,
            'is_active' => true,
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'subscription_package_id' => $package->id,
            'order_id' => 'TRX-GOLD-99',
            'gross_amount' => 75000,
            'status' => 'success',
            'payment_type' => 'manual',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('tryout.results.show', $session->id));

        $response->assertStatus(200);
        $response->assertSee('Analisis Cerdas');
        $response->assertSee('radarChart');
        $response->assertSee('Estimasi Peluang Lolos:');
        $response->assertSee('Unduh Kartu Story (IG/WA 9:16)');
        $response->assertSee('storyCard');
        $response->assertSee('Nasionalisme');
    }
}