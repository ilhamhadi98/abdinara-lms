<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Subtopic;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSeoToolsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_can_access_skd_calculator_and_view_passing_grade_rules(): void
    {
        $response = $this->get(route('practice.calculator'));

        $response->assertStatus(200);
        $response->assertSee('Kalkulator Skor');
        $response->assertSee('Passing Grade');
        $response->assertSee('TWK (Tes Wawasan Kebangsaan)');
        $response->assertSee('TIU (Tes Intelegensia Umum)');
        $response->assertSee('TKP (Tes Karakteristik Pribadi)');
        $response->assertSee('Ambang Batas: 65');
        $response->assertSee('Ambang Batas: 80');
        $response->assertSee('Ambang Batas: 166');
        $response->assertSee('https://schema.org', false);
        $response->assertSee('WebApplication', false);
    }

    public function test_public_can_access_kisi_kisi_page_and_download_guidelines(): void
    {
        $cat = Category::create(['name' => 'TWK']);
        Subtopic::create(['category_id' => $cat->id, 'name' => 'Bela Negara']);

        $response = $this->get(route('practice.kisi-kisi'));

        $response->assertStatus(200);
        $response->assertSee('Kisi-Kisi Resmi');
        $response->assertSee('Permenpan-RB');
        $response->assertSee('110 Soal');
        $response->assertSee('100 Menit');
        $response->assertSee('Cetak / Simpan PDF');
        $response->assertSee('Nasionalisme');
        $response->assertSee('Kemampuan Verbal');
        $response->assertSee('Pelayanan Publik');
    }

    public function test_sitemap_includes_calculator_and_kisi_kisi_urls(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('/kalkulator-skd', false);
        $response->assertSee('/kisi-kisi-cpns-2026', false);
        $response->assertSee('/latihan-soal', false);
    }

    public function test_landing_page_includes_og_banner_and_schema(): void
    {
        $response = $this->get(url('/'));

        $response->assertStatus(200);
        $response->assertSee('images/og-banner.png');
        $response->assertSee('Kalkulator SKD');
        $response->assertSee('Kisi-Kisi Resmi 2026');
        $response->assertSee('https://schema.org', false);
        $response->assertSee('EducationalOrganization', false);
    }
}