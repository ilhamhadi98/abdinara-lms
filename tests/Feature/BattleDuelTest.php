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

    public function test_user_can_create_battle_room_and_view_arena(): void
    {
        $this->createSampleQuestion();

        $user = User::factory()->create();
        $user->assignRole('member');

        $response = $this->actingAs($user)->post(route('battle.create'));

        $battle = Battle::first();
        $this->assertNotNull($battle);
        $this->assertEquals($user->id, $battle->player1_id);
        $this->assertEquals('waiting', $battle->status);

        $response->assertRedirect(route('battle.arena', $battle->id));
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