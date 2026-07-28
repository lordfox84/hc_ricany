<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Team;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::publishedFor(app()->getLocale())->limit(8)->get();
        $featured = $articles->firstWhere('is_featured', true) ?? $articles->first();
        $teams    = Team::active()->get();

        return view('home', compact('articles', 'featured', 'teams'));
    }
}
