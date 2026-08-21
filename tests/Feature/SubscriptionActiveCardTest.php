<?php

namespace Tests\Feature;

use App\Models\SubscriptionPackage;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionActiveCardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);

        $this->user = User::factory()->create();
        $this->user->assignRole('member');

        SubscriptionPackage::create([
            'name'          => 'Paket 30 Hari',
            'description'   => 'Akses penuh selama 30 hari',
            'price'         => 50000,
            'duration_days' => 30,
            'is_active'     => true,
        ]);
    }

    public function test_subscribed_user_sees_active_subscription_card_with_date_and_days_remaining(): void
    {
        $expiresAt = now()->addDays(20);
        $this->user->update(['subscription_expires_at' => $expiresAt]);

        $response = $this->actingAs($this->user)->get('/subscription');

        $response->assertStatus(200);
        $response->assertSee('Langganan Aktif');
        $response->assertSee('Masa Aktif Sampai:');
        $response->assertSee($expiresAt->translatedFormat('d F Y'));
        $response->assertSee('Sisa 20 Hari Lagi');
        $response->assertSee('Riwayat Transaksi');
    }

    public function test_unsubscribed_user_does_not_see_active_subscription_card(): void
    {
        $this->user->update(['subscription_expires_at' => null]);

        $response = $this->actingAs($this->user)->get('/subscription');

        $response->assertStatus(200);
        $response->assertDontSee('Status: Langganan Aktif');
        $response->assertDontSee('Sisa 20 Hari Lagi');
        $response->assertSee('Status Akun: Gratis (Tidak Aktif)');
    }

    public function test_expired_subscription_user_does_not_see_active_subscription_card(): void
    {
        $this->user->update(['subscription_expires_at' => now()->subDay()]);

        $response = $this->actingAs($this->user)->get('/subscription');

        $response->assertStatus(200);
        $response->assertDontSee('Status: Langganan Aktif');
        $response->assertSee('Status Akun: Gratis (Tidak Aktif)');
    }
}
