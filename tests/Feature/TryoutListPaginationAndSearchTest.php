<?php

namespace Tests\Feature;

use App\Livewire\TryoutList;
use App\Models\Category;
use App\Models\Subtopic;
use App\Models\Tryout;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TryoutListPaginationAndSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        Tryout::query()->delete();

        $this->member = User::factory()->create([
            'subscription_expires_at' => now()->addDays(30),
        ]);
        $this->member->assignRole('member');
    }


    public function test_tryout_list_is_ordered_ascending_by_id(): void
    {
        // Buat tryouts
        $t1 = Tryout::create(['title' => 'Tryout Alpha 1', 'duration_minutes' => 30, 'total_questions' => 10, 'is_active' => true]);
        $t2 = Tryout::create(['title' => 'Tryout Beta 2', 'duration_minutes' => 30, 'total_questions' => 10, 'is_active' => true]);
        $t3 = Tryout::create(['title' => 'Tryout Gamma 3', 'duration_minutes' => 30, 'total_questions' => 10, 'is_active' => true]);

        $this->actingAs($this->member);

        Livewire::test(TryoutList::class)
            ->assertSeeInOrder([$t1->title, $t2->title, $t3->title]);
    }

    public function test_tryout_list_pagination_and_direct_page_3_navigation(): void
    {
        // Buat 36 tryouts (12 per page -> 3 pages)
        for ($i = 1; $i <= 36; $i++) {
            Tryout::create([
                'title'            => sprintf('Tryout CAT Nomor %02d', $i),
                'duration_minutes' => 60,
                'total_questions'  => 50,
                'is_active'        => true,
            ]);
        }

        $this->actingAs($this->member);

        // Halaman 1 harus melihat nomor 01 s/d 12
        Livewire::test(TryoutList::class)
            ->assertSee('Tryout CAT Nomor 01')
            ->assertSee('Tryout CAT Nomor 12')
            ->assertDontSee('Tryout CAT Nomor 25');

        // Buka halaman 3 via URL / parameter page=3
        $response = $this->get('/tryout?page=3');
        $response->assertStatus(200);
        $response->assertSee('Tryout CAT Nomor 25');
        $response->assertSee('Tryout CAT Nomor 36');
        $response->assertDontSee('Tryout CAT Nomor 01');

        // Test Livewire component jumping to page 3
        Livewire::test(TryoutList::class)
            ->set('jumpPage', 3)
            ->call('jumpToPage')
            ->assertSee('Tryout CAT Nomor 25')
            ->assertSee('Tryout CAT Nomor 36')
            ->assertDontSee('Tryout CAT Nomor 01');
    }

    public function test_search_tryout_by_title(): void
    {
        Tryout::create(['title' => 'Simulasi SKD CPNS 2026', 'duration_minutes' => 90, 'total_questions' => 110, 'is_active' => true]);
        Tryout::create(['title' => 'Tryout Kedinasan IPDN', 'duration_minutes' => 90, 'total_questions' => 110, 'is_active' => true]);
        Tryout::create(['title' => 'Simulasi PPPK Guru', 'duration_minutes' => 90, 'total_questions' => 110, 'is_active' => true]);

        $this->actingAs($this->member);

        Livewire::test(TryoutList::class)
            ->set('search', 'CPNS')
            ->assertSee('Simulasi SKD CPNS 2026')
            ->assertDontSee('Tryout Kedinasan IPDN')
            ->assertDontSee('Simulasi PPPK Guru');
    }

    public function test_search_tryout_by_category_or_subtopic(): void
    {
        $category = Category::create(['name' => 'TIU']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Silogisme']);

        Tryout::create([
            'title'            => 'Latihan Khusus Penalaran',
            'category_id'      => $category->id,
            'subtopic_id'      => $subtopic->id,
            'duration_minutes' => 30,
            'total_questions'  => 20,
            'is_active'        => true,
        ]);

        Tryout::create([
            'title'            => 'Latihan Wawasan Kebangsaan',
            'duration_minutes' => 30,
            'total_questions'  => 20,
            'is_active'        => true,
        ]);

        $this->actingAs($this->member);

        Livewire::test(TryoutList::class)
            ->set('search', 'Silogisme')
            ->assertSee('Latihan Khusus Penalaran')
            ->assertDontSee('Latihan Wawasan Kebangsaan');
    }

    public function test_clearing_search_resets_results(): void
    {
        Tryout::create(['title' => 'Tryout A', 'duration_minutes' => 30, 'total_questions' => 10, 'is_active' => true]);
        Tryout::create(['title' => 'Tryout B', 'duration_minutes' => 30, 'total_questions' => 10, 'is_active' => true]);

        $this->actingAs($this->member);

        Livewire::test(TryoutList::class)
            ->set('search', 'Tryout A')
            ->assertSee('Tryout A')
            ->assertDontSee('Tryout B')
            ->call('clearSearch')
            ->assertSet('search', '')
            ->assertSee('Tryout A')
            ->assertSee('Tryout B');
    }
}
