<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PublicPracticeController extends Controller
{
    /**
     * Display the main SEO directory of all categories and subtopics.
     */
    public function index()
    {
        $categories = Category::withCount('subtopics')
            ->with(['subtopics' => function ($query) {
                $query->withCount('questions');
            }])
            ->get();

        $totalQuestions = Question::count();
        $totalSubtopics = Subtopic::count();

        return view('practice.index', compact('categories', 'totalQuestions', 'totalSubtopics'));
    }

    /**
     * Display all subtopics and guides for a specific category (TWK, TIU, TKP).
     */
    public function category(string $categorySlug)
    {
        $categories = Category::with(['subtopics' => function ($query) {
            $query->withCount('questions');
        }])->get();

        $category = $categories->first(function ($cat) use ($categorySlug) {
            return Str::slug($cat->name) === strtolower($categorySlug);
        });

        if (! $category) {
            abort(404);
        }

        $otherCategories = $categories->filter(fn ($c) => $c->id !== $category->id);

        return view('practice.category', compact('category', 'otherCategories'));
    }

    /**
     * Display interactive practice questions and JSON-LD schema for a specific subtopic.
     */
    public function subtopic(string $categorySlug, string $subtopicSlug)
    {
        $categories = Category::all();
        $category = $categories->first(function ($cat) use ($categorySlug) {
            return Str::slug($cat->name) === strtolower($categorySlug);
        });

        if (! $category) {
            abort(404);
        }

        $subtopic = Subtopic::where('category_id', $category->id)
            ->get()
            ->first(function ($sub) use ($subtopicSlug) {
                return Str::slug($sub->name) === strtolower($subtopicSlug);
            });

        if (! $subtopic) {
            abort(404);
        }

        // Fetch up to 5 sample questions with explanations
        $questions = Question::where('subtopic_id', $subtopic->id)
            ->orderBy('id', 'asc')
            ->take(5)
            ->get();

        // Other subtopics in this category for strong internal SEO linking
        $siblingSubtopics = Subtopic::where('category_id', $category->id)
            ->where('id', '!=', $subtopic->id)
            ->withCount('questions')
            ->get();

        return view('practice.subtopic', compact('category', 'subtopic', 'questions', 'siblingSubtopics'));
    }

    /**
     * Generate dynamic sitemap.xml for Google Search Console and crawlers.
     */
    public function sitemap()
    {
        $baseUrl = rtrim(config('app.url', 'https://cat.abdinara.id'), '/');
        $now = now()->toAtomString();

        $categories = Category::with('subtopics')->get();

        $urls = [];

        // 1. Static high priority pages
        $urls[] = [
            'loc' => $baseUrl . '/',
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/latihan-soal',
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/tryout',
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '0.8',
        ];
        $urls[] = [
            'loc' => $baseUrl . '/subscription',
            'lastmod' => $now,
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];

        // 2. Programmatic Category URLs
        foreach ($categories as $category) {
            $catSlug = Str::slug($category->name);
            $urls[] = [
                'loc' => $baseUrl . '/latihan-soal/' . $catSlug,
                'lastmod' => $category->updated_at?->toAtomString() ?? $now,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];

            // 3. Programmatic Subtopic URLs
            foreach ($category->subtopics as $subtopic) {
                $subSlug = Str::slug($subtopic->name);
                $urls[] = [
                    'loc' => $baseUrl . '/latihan-soal/' . $catSlug . '/' . $subSlug,
                    'lastmod' => $subtopic->updated_at?->toAtomString() ?? $now,
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'text/xml',
        ]);
    }
}