<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /** Admin: list all articles */
    public function index()
    {
        $articles = Article::latest()->get();
        return view('admin.articles.index', compact('articles'));
    }

    /** Admin: show create form */
    public function create()
    {
        $categories = Category::orderBy('name_cs')->get();
        return view('admin.articles.create', compact('categories'));
    }

    /** Admin: store new article */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title_cs'    => 'required|string|max:255',
            'title_en'    => 'nullable|string|max:255',
            'excerpt_cs'  => 'nullable|string|max:500',
            'excerpt_en'  => 'nullable|string|max:500',
            'body_cs'     => 'nullable|string',
            'body_en'     => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:4096',
            'is_featured' => 'boolean',
            'is_published'=> 'boolean',
        ]);

        // Derive text category fields from the selected Category model
        if (!empty($data['category_id'])) {
            $cat = Category::find($data['category_id']);
            $data['category_cs'] = $cat->name_cs;
            $data['category_en'] = $cat->name_en ?? $cat->name_cs;
        } else {
            $data['category_cs'] = null;
            $data['category_en'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $data['user_id']      = Auth::id();
        $data['published_at'] = now();
        $data['is_featured']  = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published', true);
        $data['title_en']     = $data['title_en'] ?? null;
        $data['excerpt_en']   = $data['excerpt_en'] ?? null;
        $data['body_en']      = $data['body_en'] ?? null;

        $article = Article::create($data);

        if ($article->is_featured) {
            Article::setFeatured($article->id);
        }

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Článek byl úspěšně publikován.');
    }

    /** Admin: show edit form */
    public function edit(Article $article)
    {
        $categories = Category::orderBy('name_cs')->get();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /** Admin: update article */
    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title_cs'    => 'required|string|max:255',
            'title_en'    => 'nullable|string|max:255',
            'excerpt_cs'  => 'nullable|string|max:500',
            'excerpt_en'  => 'nullable|string|max:500',
            'body_cs'     => 'nullable|string',
            'body_en'     => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|max:4096',
            'is_featured' => 'boolean',
            'is_published'=> 'boolean',
        ]);

        // Derive text category fields from the selected Category model
        if (!empty($data['category_id'])) {
            $cat = Category::find($data['category_id']);
            $data['category_cs'] = $cat->name_cs;
            $data['category_en'] = $cat->name_en ?? $cat->name_cs;
        } else {
            $data['category_cs'] = null;
            $data['category_en'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $data['is_featured']  = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published', true);

        $article->update($data);

        if ($article->is_featured) {
            Article::setFeatured($article->id);
        }

        return redirect()->route('admin.articles.index')
                         ->with('success', 'Článek byl úspěšně uložen.');
    }

    /** Admin: delete article */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')
                         ->with('success', 'Článek byl smazán.');
    }
}
