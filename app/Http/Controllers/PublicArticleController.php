<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Team;

class PublicArticleController extends Controller
{
    public function index()
    {
        $articles = Article::publishedFor(app()->getLocale())->paginate(12);
        $teams    = Team::orderBy('sort_order')->get();

        return view('articles.index', compact('articles', 'teams'));
    }

    public function show(Article $article)
    {
        abort_unless($article->is_published, 404);
        abort_unless($article->isCompleteFor(app()->getLocale()), 404);

        $teams = Team::orderBy('sort_order')->get();

        return view('articles.show', compact('article', 'teams'));
    }
}
