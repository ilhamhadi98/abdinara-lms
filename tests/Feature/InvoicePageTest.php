<?php

namespace Tests\Feature;

use App\Models\SubscriptionPackage;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_view_invoice_page_with_abdinara_logo_and_brand(): void
    {
        $user = User::factory()->create();
        $user->assignRole('member');

        $package = SubscriptionPackage::create([
            'name' => 'Gold',
            'duration_days' => 90,
            'price' => 75000,
            'description' => 'Persiapan menjadi abdi negara',
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'subscription_package_id' => $package->id,
            'order_id' => 'TRX-1786009432-3-3',
            'gross_amount' => 75000,
            'status' => 'success',
            'payment_type' => 'manual',
        ]);

        $response = $this->actingAs($user)->get(route('subscription.invoice', $transaction->id));

        $response->assertStatus(200);
        $response->assertSee('favicon.ico');
        $response->assertSee('TRX-1786009432-3-3');
        $response->assertSee('Gold');
        $response->assertSee('75.000');
        $response->assertSee('LUNAS / BERHASIL');
        $response->assertSee('Download Gambar (PNG)');
        $response->assertSee('Cetak / PDF');
    }

    public function test_user_cannot_view_another_users_invoice(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $package = SubscriptionPackage::create([
            'name' => 'Silver',
            'duration_days' => 30,
            'price' => 50000,
            'description' => 'Paket Silver',
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'user_id' => $owner->id,
            'subscription_package_id' => $package->id,
            'order_id' => 'TRX-OWNER-123',
            'gross_amount' => 50000,
            'status' => 'success',
            'payment_type' => 'qris',
        ]);

        $response = $this->actingAs($otherUser)->get(route('subscription.invoice', $transaction->id));
        $response->assertStatus(403);
    }
}