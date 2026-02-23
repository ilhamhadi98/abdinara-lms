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

class AdminTryoutTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->member = User::factory()->create();
        $this->member->assignRole('member');
    }

    // -----------------------------------------------------------------------
    // Access Control
    // -----------------------------------------------------------------------

    public function test_guest_cannot_access_admin_tryouts(): void
    {
        $this->get('/admin/tryouts')->assertRedirect('/login');
    }

    public function test_member_cannot_access_admin_tryouts(): void
    {
        $this->actingAs($this->member)
             ->get('/admin/tryouts')
             ->assertForbidden();
    }

    public function test_admin_can_access_tryout_index(): void
    {
        $this->actingAs($this->admin)
             ->get('/admin/tryouts')
             ->assertStatus(200);
    }

    public function test_admin_can_access_create_tryout(): void
    {
        $this->actingAs($this->admin)
             ->get('/admin/tryouts/create')
             ->assertStatus(200);
    }

    // -----------------------------------------------------------------------
    // Tryout CRUD
    // -----------------------------------------------------------------------

    public function test_admin_can_create_tryout_with_random_questions(): void
    {
        // Seed soal untuk dipilih
        $category = Category::create(['name' => 'TWK']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Pancasila']);

        for ($i = 1; $i <= 5; $i++) {
            Question::create([
                'subtopic_id'   => $subtopic->id,
                'question_text' => "Soal nomor {$i}",
                'option_a'      => 'A', 'option_b' => 'B', 'option_c' => 'C',
                'option_d'      => 'D', 'option_e' => 'E',
                'correct_answer'=> 'A',
                'difficulty'    => 1,
            ]);
        }

        $this->actingAs($this->admin)
             ->post('/admin/tryouts', [
                 'title'            => 'Try Out TWK 1',
                 'duration_minutes' => 60,
                 'total_questions'  => 3,
             ])
             ->assertRedirect('/admin/tryouts');

        $this->assertDatabaseHas('tryouts', ['title' => 'Try Out TWK 1']);

        $tryout = Tryout::where('title', 'Try Out TWK 1')->first();
        $this->assertEquals(3, $tryout->questions()->count());
    }

    public function test_cannot_create_tryout_if_not_enough_questions(): void
    {
        $this->actingAs($this->admin)
             ->post('/admin/tryouts', [
                 'title'            => 'Test Tryout',
                 'duration_minutes' => 30,
                 'total_questions'  => 100, // tidak ada soal
             ])
             ->assertSessionHasErrors('total_questions');
    }

    public function test_admin_can_publish_and_unpublish_tryout(): void
    {
        $tryout = Tryout::create([
            'title'            => 'Draft Tryout',
            'duration_minutes' => 90,
            'total_questions'  => 10,
            'is_active'        => false,
        ]);

        // Publish
        $this->actingAs($this->admin)
             ->patch("/admin/tryouts/{$tryout->id}/publish")
             ->assertRedirect();

        $this->assertTrue($tryout->fresh()->is_active);

        // Unpublish
        $this->actingAs($this->admin)
             ->patch("/admin/tryouts/{$tryout->id}/publish")
             ->assertRedirect();

        $this->assertFalse($tryout->fresh()->is_active);
    }

    public function test_admin_can_delete_tryout(): void
    {
        $tryout = Tryout::create([
            'title'            => 'Delete Me',
            'duration_minutes' => 60,
            'total_questions'  => 10,
        ]);

        $this->actingAs($this->admin)
             ->delete("/admin/tryouts/{$tryout->id}")
             ->assertRedirect();

        $this->assertDatabaseMissing('tryouts', ['id' => $tryout->id]);
    }

    // -----------------------------------------------------------------------
    // Question & Category Management
    // -----------------------------------------------------------------------

    public function test_admin_can_access_question_index(): void
    {
        $this->actingAs($this->admin)
             ->get('/admin/questions')
             ->assertStatus(200);
    }

    public function test_admin_can_create_question(): void
    {
        $category = Category::create(['name' => 'TIU']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Logika']);

        $this->actingAs($this->admin)
             ->post('/admin/questions', [
                 'subtopic_id'    => $subtopic->id,
                 'question_text'  => 'Berapa 2 + 2?',
                 'option_a'       => '3',
                 'option_b'       => '4',
                 'option_c'       => '5',
                 'option_d'       => '6',
                 'option_e'       => '7',
                 'correct_answer' => 'B',
                 'difficulty'     => 1,
             ])
             ->assertRedirect('/admin/questions');

        $this->assertDatabaseHas('questions', ['question_text' => 'Berapa 2 + 2?']);
    }

    public function test_admin_can_add_category(): void
    {
        $this->actingAs($this->admin)
             ->post('/admin/categories', ['name' => 'TKP'])
             ->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'TKP']);
    }

    public function test_correct_answer_not_exposed_in_question_model(): void
    {
        $category = Category::create(['name' => 'Test']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Sub']);
        $question = Question::create([
            'subtopic_id'   => $subtopic->id,
            'question_text' => 'Test question',
            'option_a'      => 'A', 'option_b' => 'B', 'option_c' => 'C',
            'option_d'      => 'D', 'option_e' => 'E',
            'correct_answer'=> 'C',
            'difficulty'    => 1,
        ]);

        // correct_answer harus hidden saat toArray/toJson
        $arr = $question->toArray();
        $this->assertArrayNotHasKey('correct_answer', $arr);
    }
}
