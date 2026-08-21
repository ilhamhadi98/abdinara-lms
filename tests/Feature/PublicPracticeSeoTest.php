<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPracticeSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_can_access_practice_index_directory(): void
    {
        $catTwk = Category::create(['name' => 'TWK']);
        Subtopic::create(['category_id' => $catTwk->id, 'name' => 'Nasionalisme']);
        Subtopic::create(['category_id' => $catTwk->id, 'name' => 'Bela Negara']);

        $catTiu = Category::create(['name' => 'TIU']);
        Subtopic::create(['category_id' => $catTiu->id, 'name' => 'Deret Angka']);

        $response = $this->get('/latihan-soal');

        $response->assertStatus(200);
        $response->assertSee('Bank Latihan Soal');
        $response->assertSee('TWK');
        $response->assertSee('TIU');
        $response->assertSee('Nasionalisme');
        $response->assertSee('Deret Angka');
    }

    public function test_public_can_access_category_page(): void
    {
        $category = Category::create(['name' => 'TWK']);
        Subtopic::create(['category_id' => $category->id, 'name' => 'Nasionalisme']);
        Subtopic::create(['category_id' => $category->id, 'name' => 'Integritas']);

        $response = $this->get('/latihan-soal/twk');

        $response->assertStatus(200);
        $response->assertSeeText('Tes Wawasan Kebangsaan (TWK)');
        $response->assertSee('Nasionalisme');
        $response->assertSee('Integritas');
    }

    public function test_public_can_access_subtopic_interactive_page_with_schema(): void
    {
        $category = Category::create(['name' => 'TWK']);
        $subtopic = Subtopic::create(['category_id' => $category->id, 'name' => 'Nasionalisme']);

        $q = Question::create([
            'subtopic_id' => $subtopic->id,
            'question_text' => 'Apa pengertian nasionalisme yang paling tepat?',
            'option_a' => 'Cinta tanah air berlebihan',
            'option_b' => 'Kesadaran keanggotaan dalam suatu bangsa untuk mencapai dan mempertahankan kemerdekaan',
            'option_c' => 'Sikap mementingkan suku sendiri',
            'option_d' => 'Pengabdian pada raja',
            'option_e' => 'Semangat kedaerahan',
            'correct_answer' => 'B',
            'explanation' => 'Nasionalisme adalah kesadaran kebangsaan yang mengikat seluruh warga negara.',
            'difficulty' => 1,
        ]);

        $response = $this->get('/latihan-soal/twk/nasionalisme');

        $response->assertStatus(200);
        $response->assertSee('Contoh Soal Nasionalisme');
        $response->assertSee('Apa pengertian nasionalisme yang paling tepat?');
        $response->assertSee('Kesadaran keanggotaan dalam suatu bangsa');
        $response->assertSee('schema.org', false);
        $response->assertSee('FAQPage', false);
        $response->assertSee('Simulasi CAT Lengkap');
    }

    public function test_public_sitemap_xml_generation(): void
    {
        $catTwk = Category::create(['name' => 'TWK']);
        Subtopic::create(['category_id' => $catTwk->id, 'name' => 'Nasionalisme']);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('/latihan-soal</loc>', false);
        $response->assertSee('/latihan-soal/twk</loc>', false);
        $response->assertSee('/latihan-soal/twk/nasionalisme</loc>', false);
    }
}