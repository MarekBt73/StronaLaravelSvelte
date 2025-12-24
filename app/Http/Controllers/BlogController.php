<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use App\Models\ArticleVisitorSession;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Article::published()
            ->recent()
            ->with(['category', 'author']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->get('kategoria')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $articles = $query->paginate(9)->withQueryString();

        $categories = Category::active()
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('name')
            ->get();

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'filters' => [
                'search' => $request->get('search', ''),
                'kategoria' => $request->get('kategoria', ''),
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        // Increment views (ogolny licznik + dzienne statystyki)
        $article->increment('views');

        // Zapisz dzienne statystyki z unikalnym uzytkownikiem
        // Try-catch aby strona dzialala nawet jesli tabele nie istnieja
        try {
            $sessionHash = ArticleVisitorSession::generateSessionHash();
            ArticleView::recordView($article->id, $sessionHash);
        } catch (\Throwable $e) {
            // Ignoruj bledy - statystyki sa opcjonalne
            report($e);
        }

        // Related articles
        $related = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->recent()
            ->limit(3)
            ->get();

        return Inertia::render('Blog/Show', [
            'article' => $article,
            'related' => $related,
        ]);
    }

    public function category(string $slug): Response
    {
        $category = Category::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $articles = Article::published()
            ->where('category_id', $category->id)
            ->recent()
            ->with(['category', 'author'])
            ->paginate(9);

        $categories = Category::active()
            ->withCount(['articles' => function ($q) {
                $q->published();
            }])
            ->having('articles_count', '>', 0)
            ->orderBy('name')
            ->get();

        return Inertia::render('Blog/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'currentCategory' => $category,
            'filters' => [
                'search' => '',
                'kategoria' => $slug,
            ],
        ]);
    }
}
