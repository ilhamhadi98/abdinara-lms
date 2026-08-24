<?php

namespace Tests\Feature;

use App\Livewire\BattleArena;
use App\Models\Battle;
use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BattleDuelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function createSampleQuestion(): Question
    {
        $cat = Category::firstOrCreate(['name' => 'TIU']);
        $sub = Subtopic::firstOrCreate(['category_id' => $cat->id, 'name' => 'Deret']);

        return Question::create([
            'subtopic_id' => $sub->id,
            'question_text' => '2, 4, 6, 8, ...',
            'option_a' => '10',
            'option_b' => '12',
            'option_c' => '14',
            'option_d' => '16',
            'option_e' => '18',
            'correct_answer' => 'A',
            'explanation' => 'Pola +2',
            'difficulty' => 1,
        ]);
    }

    public function test_user_can_access_battle_lobby(): void
    {
        $user = User::factory()->create();
        $user->assignRole('member');

        $response = $this->actingAs($user)->get(route('battle.index'));

        $response->assertStatus(200);
        $response->assertSee('CAT Battle 1 vs 1');
        $response->assertSee('Cari Lawan Cepat');
        $response->assertSee('Buat Room');
        $response->assertSee('Ajak Teman');
    }

    public function test_user_can_create_battle_room_and_view_arena_waiting_screen(): void
    {
        $q = $this->createSampleQuestion();

        $user = User::factory()->create();
        $user->assignRole('member');

        $response = $this->actingAs($user)->post(route('battle.create'));

        $battle = Battle::first();
        $this->assertNotNull($battle);
        $this->assertEquals($user->id, $battle->player1_id);
        $this->assertEquals('waiting', $battle->status);

        $response->assertRedirect(route('battle.arena', $battle->id));

        // In Livewire, Player 1 sees waiting room screen with room code and questions are locked
        Livewire::actingAs($user)
            ->test(BattleArena::class, ['battle' => $battle])
            ->assertSet('isWaitingForOpponent', true)
            ->assertSee('Ruang Tunggu Arena Duel CAT')
            ->assertSee($battle->room_code)
            ->assertSee('Menunggu Lawan Bergabung')
            ->call('selectAnswer', 'A')
            ->assertSet('isAnswered', false); // cannot answer while waiting
    }

    public function test_user_can_join_room_via_code_or_direct_link_and_activates_duel(): void
    {
        $q = $this->createSampleQuestion();

        $player1 = User::factory()->create();
        $player2 = User::factory()->create();

        $battle = Battle::create([
            'room_code' => 'DUEL99',
            'player1_id' => $player1->id,
            'status' => 'waiting',
        ]);
        $battle->questions()->attach($q->id, ['sort_order' => 1]);

        // Player 2 joins via direct link
        $response = $this->actingAs($player2)->get(route('battle.join.direct', 'DUEL99'));

        $response->assertRedirect(route('battle.arena', $battle->id));

        $battle->refresh();
        $this->assertEquals('active', $battle->status);
        $this->assertEquals($player2->id, $battle->player2_id);

        // Player 1 Livewire poll triggers and starts duel
        Livewire::actingAs($player1)
            ->test(BattleArena::class, ['battle' => $battle])
            ->assertSet('isWaitingForOpponent', false)
            ->assertSee('2, 4, 6, 8, ...');
    }

    public function test_waiting_room_cancels_on_user_action_or_timeout(): void
    {
        $player1 = User::factory()->create();

        $battle = Battle::create([
            'room_code' => 'CNCL12',
            'player1_id' => $player1->id,
            'status' => 'waiting',
        ]);

        Livewire::actingAs($player1)
            ->test(BattleArena::class, ['battle' => $battle])
            ->call('cancelRoom')
            ->assertRedirect(route('battle.index'));

        $battle->refresh();
        $this->assertEquals('cancelled', $battle->status);
    }

    public function test_user_can_quick_match_with_ai_opponent(): void
    {
        $this->createSampleQuestion();

        $user = User::factory()->create();
        $user->assignRole('member');

        $response = $this->actingAs($user)->get(route('battle.quick'));

        $battle = Battle::first();
        $this->assertNotNull($battle);
        $this->assertTrue($battle->is_bot);
        $this->assertEquals('active', $battle->status);
        $this->assertNotNull($battle->player2_name);

        $response->assertRedirect(route('battle.arena', $battle->id));
    }

    public function test_player_can_answer_in_livewire_arena_and_finish(): void
    {
        $q = $this->createSampleQuestion();

        $user = User::factory()->create();
        $user->assignRole('member');

        $battle = Battle::create([
            'room_code' => 'TEST12',
            'player1_id' => $user->id,
            'player2_name' => 'AI Bot',
            'is_bot' => true,
            'status' => 'active',
        ]);
        $battle->questions()->attach($q->id, ['sort_order' => 1]);

        Livewire::actingAs($user)
            ->test(BattleArena::class, ['battle' => $battle])
            ->assertSet('isWaitingForOpponent', false)
            ->assertSee('2, 4, 6, 8, ...')
            ->call('selectAnswer', 'A')
            ->assertSet('isAnswered', true)
            ->assertSet('selectedOption', 'A')
            ->call('nextQuestion')
            ->assertSet('isFinished', true)
            ->assertSee('VICTORY!');

        $battle->refresh();
        $this->assertEquals('finished', $battle->status);
        $this->assertGreaterThan(0, $battle->player1_score);
    }
}