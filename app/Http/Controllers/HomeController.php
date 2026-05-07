<?php

namespace App\Http\Controllers;

use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::published()->limit(8)->get();
        $featured = $articles->firstWhere('is_featured', true) ?? $articles->first();

        return view('home', compact('articles', 'featured'));
    }
}
