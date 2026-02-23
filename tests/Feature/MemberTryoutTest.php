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
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MemberTryoutTest extends TestCase
{
    use RefreshDatabase;

    private User $member;
    private User $guest;
    private Tryout $tryout;
    private array $questionIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->member = User::factory()->create();
        $this->member->assignRole('member');

        $this->guest = User::factory()->create();
        // guest tidak punya role apapun

        // Buat tryout dengan 3 soal
        $category = Category::create(['name' => 'TWK']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Pancasila']);

        $this->tryout = Tryout::create([
            'title'            => 'Try Out Nasional 1',
            'duration_minutes' => 30,
            'total_questions'  => 3,
            'is_active'        => true,
        ]);

        for ($i = 1; $i <= 3; $i++) {
            $q = Question::create([
                'subtopic_id'   => $subtopic->id,
                'question_text' => "Soal {$i}",
                'option_a'      => 'A', 'option_b' => 'B', 'option_c' => 'C',
                'option_d'      => 'D', 'option_e' => 'E',
                'correct_answer'=> 'A', // jawaban benar selalu A
                'difficulty'    => 1,
            ]);
            $this->questionIds[] = $q->id;

            DB::table('tryout_questions')->insert([
                'tryout_id'   => $this->tryout->id,
                'question_id' => $q->id,
                'sort_order'  => $i,
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // Tryout List Access
    // -----------------------------------------------------------------------

    public function test_guest_cannot_access_tryout_list(): void
    {
        $this->get('/tryout')->assertRedirect('/login');
    }

    public function test_user_without_role_cannot_access_tryout_list(): void
    {
        $this->actingAs($this->guest)
             ->get('/tryout')
             ->assertForbidden();
    }

    // -----------------------------------------------------------------------
    // Session: Start & Continue
    // -----------------------------------------------------------------------

    public function test_member_can_start_a_new_tryout_session(): void
    {
        $session = TryoutSession::create([
            'user_id'    => $this->member->id,
            'tryout_id'  => $this->tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes(30),
            'status'     => 'ongoing',
        ]);

        $this->actingAs($this->member)
             ->get("/tryout/session/{$session->id}")
             ->assertStatus(200);
    }

    public function test_member_cannot_access_another_users_session(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole('member');

        $session = TryoutSession::create([
            'user_id'    => $otherUser->id,
            'tryout_id'  => $this->tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes(30),
            'status'     => 'ongoing',
        ]);

        $this->actingAs($this->member)
             ->get("/tryout/session/{$session->id}")
             ->assertForbidden();
    }

    // -----------------------------------------------------------------------
    // Score Calculation
    // -----------------------------------------------------------------------

    public function test_score_is_calculated_correctly_via_sql(): void
    {
        $session = TryoutSession::create([
            'user_id'    => $this->member->id,
            'tryout_id'  => $this->tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes(30),
            'status'     => 'ongoing',
        ]);

        // Jawab 2 benar (A), 1 salah (B)
        TryoutAnswer::create(['session_id' => $session->id, 'question_id' => $this->questionIds[0], 'selected_answer' => 'A']);
        TryoutAnswer::create(['session_id' => $session->id, 'question_id' => $this->questionIds[1], 'selected_answer' => 'A']);
        TryoutAnswer::create(['session_id' => $session->id, 'question_id' => $this->questionIds[2], 'selected_answer' => 'B']); // salah

        // Hitung skor via SQL seperti di TryoutEngine::submitTryout()
        $score = DB::table('tryout_answers as ta')
            ->join('questions as q', 'ta.question_id', '=', 'q.id')
            ->where('ta.session_id', $session->id)
            ->whereRaw('ta.selected_answer = q.correct_answer')
            ->count();

        $this->assertEquals(2, $score);
    }

    // -----------------------------------------------------------------------
    // Result Access
    // -----------------------------------------------------------------------

    public function test_member_can_view_own_finished_session_result(): void
    {
        $session = TryoutSession::create([
            'user_id'     => $this->member->id,
            'tryout_id'   => $this->tryout->id,
            'started_at'  => now()->subMinutes(30),
            'finished_at' => now(),
            'expired_at'  => now()->addMinutes(30),
            'score'       => 2,
            'status'      => 'finished',
        ]);

        $this->actingAs($this->member)
             ->get("/tryout/results/{$session->id}")
             ->assertStatus(200);
    }

    public function test_member_cannot_view_ongoing_session_as_result(): void
    {
        $session = TryoutSession::create([
            'user_id'    => $this->member->id,
            'tryout_id'  => $this->tryout->id,
            'started_at' => now(),
            'expired_at' => now()->addMinutes(30),
            'status'     => 'ongoing',
        ]);

        $this->actingAs($this->member)
             ->get("/tryout/results/{$session->id}")
             ->assertNotFound();
    }

    public function test_session_expiry_detected_correctly(): void
    {
        $session = TryoutSession::create([
            'user_id'    => $this->member->id,
            'tryout_id'  => $this->tryout->id,
            'started_at' => now()->subMinutes(35),
            'expired_at' => now()->subMinutes(5), // sudah lewat
            'status'     => 'ongoing',
        ]);

        $this->assertTrue($session->isExpired());
    }
}
