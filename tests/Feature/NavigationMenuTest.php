<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_desktop_and_mobile_navigation_renders_all_gamification_and_learning_menus(): void
    {
        $user = User::factory()->create();
        $user->assignRole('member');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);

        // Desktop Dropdowns & Links
        $response->assertSee('Tryout CAT');
        $response->assertSee('Latihan Soal');
        $response->assertSee('Kompetisi');
        $response->assertSee('Liga Tryout Mingguan');
        $response->assertSee('CAT Battle 1 vs 1');

        // Mobile Bottom Nav & Offcanvas
        $response->assertSee('Beranda');
        $response->assertSee('Liga');
        $response->assertSee('Duel');
        $response->assertSee('Menu Lengkap LMS');
        $response->assertSee('Bank Latihan Soal Gratis');
        $response->assertSee('Hasil & Analisis', false);
        $response->assertSee('Paket Premium VIP');
        $response->assertSee('Riwayat Langganan');
    }

    public function test_guest_navigation_renders_public_features(): void
    {
        $response = $this->get(url('/'));

        $response->assertStatus(200);
        $response->assertSee('Latihan Soal');
        $response->assertSee('🏆 Liga Tryout');
        $response->assertSee('⚔️ Duel 1 vs 1');
    }
}