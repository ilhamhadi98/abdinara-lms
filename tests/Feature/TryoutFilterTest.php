<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use App\Models\Tryout;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TryoutFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        // Create categories
        $catTwk = Category::create(['name' => 'TWK']);
        $catTiu = Category::create(['name' => 'TIU']);

        $subTwk = Subtopic::create(['category_id' => $catTwk->id, 'name' => 'Pancasila']);
        $subTiu = Subtopic::create(['category_id' => $catTiu->id, 'name' => 'Logika']);

        // Seed 10 TWK questions (Difficulty 1)
        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'subtopic_id'   => $subTwk->id,
                'question_text' => "TWK Soal {$i}",
                'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
                'correct_answer'=> 'A', 'difficulty' => 1,
            ]);
        }

        // Seed 5 TIU questions (Difficulty 2)
        for ($i = 1; $i <= 5; $i++) {
            Question::create([
                'subtopic_id'   => $subTiu->id,
                'question_text' => "TIU Soal {$i}",
                'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D', 'option_e' => 'E',
                'correct_answer'=> 'B', 'difficulty' => 2,
            ]);
        }
    }

    public function test_can_create_tryout_filtering_by_category(): void
    {
        $twkCategory = Category::where('name', 'TWK')->first();

        $this->actingAs($this->admin)
            ->post('/admin/tryouts/create', [
                'title' => 'Tryout TWK Only',
                'duration_minutes' => 60,
                'total_questions' => 5,
                'category_id' => $twkCategory->id,
                'is_active' => true,
            ]);

        $tryout = Tryout::where('title', 'Tryout TWK Only')->first();
        $this->assertNotNull($tryout);
        $this->assertEquals(5, $tryout->questions()->count());
        
        // Verify all questions belong to TWK subtopic
        foreach ($tryout->questions as $q) {
            $this->assertEquals($twkCategory->id, $q->subtopic->category_id);
        }
    }

    public function test_can_create_tryout_filtering_by_difficulty(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/tryouts/create', [
                'title' => 'Tryout Hard Only',
                'duration_minutes' => 60,
                'total_questions' => 3,
                'difficulty' => 2, // 'TIU Soal' are difficulty 2
            ]);

        $tryout = Tryout::where('title', 'Tryout Hard Only')->first();
        $this->assertEquals(3, $tryout->questions()->count());
        
        foreach ($tryout->questions as $q) {
            $this->assertEquals(2, $q->difficulty);
        }
    }

    public function test_create_tryout_with_mix_all_picks_from_anywhere(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/tryouts/create', [
                'title' => 'Mix Tryout',
                'duration_minutes' => 60,
                'total_questions' => 15,
                'category_id' => null,
                'difficulty' => null,
            ]);

        $tryout = Tryout::where('title', 'Mix Tryout')->first();
        $this->assertEquals(15, $tryout->questions()->count());
    }
}
