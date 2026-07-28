<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampController extends Controller
{
    public function index()
    {
        $camps = Camp::latest('published_at')->get();
        return view('admin.camps.index', compact('camps'));
    }

    public function create()
    {
        return view('admin.camps.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'              => 'required|in:letni,skills',
            'title_cs'          => 'required|string|max:255',
            'title_en'          => 'nullable|string|max:255',
            'excerpt_cs'        => 'nullable|string|max:500',
            'excerpt_en'        => 'nullable|string|max:500',
            'body_cs'           => 'nullable|string',
            'body_en'           => 'nullable|string',
            'poster'            => 'nullable|image|max:8192',
            'date_from'         => 'nullable|date',
            'date_to'           => 'nullable|date|after_or_equal:date_from',
            'capacity'          => 'nullable|integer|min:1',
            'is_published'      => 'boolean',
            'registration_open' => 'boolean',
            'variants'          => 'nullable|array',
            'variants.*'        => 'string|max:300',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('camps', 'public');
        }

        // Filtrovat prázdné varianty
        $variants = array_values(array_filter($request->input('variants', []), fn($v) => trim($v) !== ''));
        $data['variants'] = empty($variants) ? null : $variants;

        $data['user_id']          = Auth::id();
        $data['published_at']     = now();
        $data['is_published']     = $request->boolean('is_published', true);
        $data['registration_open']= $request->boolean('registration_open');

        Camp::create($data);
        return redirect()->route('admin.camps.index')->with('success', 'Kemp byl přidán.');
    }

    public function edit(Camp $camp)
    {
        return view('admin.camps.edit', compact('camp'));
    }

    public function update(Request $request, Camp $camp)
    {
        $data = $request->validate([
            'type'              => 'required|in:letni,skills',
            'title_cs'          => 'required|string|max:255',
            'title_en'          => 'nullable|string|max:255',
            'excerpt_cs'        => 'nullable|string|max:500',
            'excerpt_en'        => 'nullable|string|max:500',
            'body_cs'           => 'nullable|string',
            'body_en'           => 'nullable|string',
            'poster'            => 'nullable|image|max:8192',
            'date_from'         => 'nullable|date',
            'date_to'           => 'nullable|date|after_or_equal:date_from',
            'capacity'          => 'nullable|integer|min:1',
            'is_published'      => 'boolean',
            'registration_open' => 'boolean',
            'variants'          => 'nullable|array',
            'variants.*'        => 'string|max:300',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('camps', 'public');
        }

        // Filtrovat prázdné varianty
        $variants = array_values(array_filter($request->input('variants', []), fn($v) => trim($v) !== ''));
        $data['variants'] = empty($variants) ? null : $variants;

        $data['is_published']      = $request->boolean('is_published', true);
        $data['registration_open'] = $request->boolean('registration_open');
        $camp->update($data);
        return redirect()->route('admin.camps.index')->with('success', 'Kemp byl uložen.');
    }

    public function destroy(Camp $camp)
    {
        $camp->delete();
        return redirect()->route('admin.camps.index')->with('success', 'Kemp byl smazán.');
    }
}
