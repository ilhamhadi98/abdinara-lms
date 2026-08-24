<?php

namespace Tests\Feature;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\Tryout;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeeklyTournamentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_autopilot_command_schedules_new_tournament(): void
    {
        $tryout = Tryout::create([
            'title' => 'Tryout Akbar SKD',
            'duration_minutes' => 100,
            'total_questions' => 110,
            'is_active' => true,
        ]);

        $this->artisan('tournament:schedule-weekly')->assertExitCode(0);

        $this->assertDatabaseHas('tournaments', [
            'tryout_id' => $tryout->id,
            'is_active' => true,
        ]);
    }

    public function test_public_can_view_tournament_page_and_leaderboard(): void
    {
        $tryout = Tryout::create([
            'title' => 'Tryout Akbar SKD',
            'duration_minutes' => 100,
            'total_questions' => 110,
            'is_active' => true,
        ]);

        $tournament = Tournament::create([
            'title' => 'Liga Tryout Nasional SKD - Edisi 1',
            'edition_number' => 1,
            'tryout_id' => $tryout->id,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'is_active' => true,
            'prizes_description' => 'Hadiah E-Wallet',
        ]);

        $user = User::factory()->create();
        $user->assignRole('member');

        TournamentParticipant::create([
            'tournament_id' => $tournament->id,
            'user_id' => $user->id,
            'score' => 450,
            'duration_seconds' => 3600,
            'rank_position' => 1,
            'is_passed' => true,
        ]);

        $response = $this->get(route('tournament.index'));

        $response->assertStatus(200);
        $response->assertSee('Liga Tryout Nasional');
        $response->assertSee('Klasemen Peringkat Nasional (Live)');
        $response->assertSee($user->name);
        $response->assertSee('450');
    }

    public function test_user_can_view_earned_certificate(): void
    {
        $user = User::factory()->create();
        $user->assignRole('member');

        $tryout = Tryout::create([
            'title' => 'Tryout Akbar SKD',
            'duration_minutes' => 100,
            'total_questions' => 110,
            'is_active' => true,
        ]);

        $tournament = Tournament::create([
            'title' => 'Liga Tryout Nasional SKD - Edisi 1',
            'edition_number' => 1,
            'tryout_id' => $tryout->id,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'is_active' => true,
        ]);

        $participant = TournamentParticipant::create([
            'tournament_id' => $tournament->id,
            'user_id' => $user->id,
            'score' => 450,
            'duration_seconds' => 3600,
            'rank_position' => 1,
            'is_passed' => true,
        ]);

        $response = $this->actingAs($user)->get(route('tournament.certificate', $participant->id));

        $response->assertStatus(200);
        $response->assertSee('SERTIFIKAT PRESTASI');
        $response->assertSee($user->name);
        $response->assertSee('450');
    }
}