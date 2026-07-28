<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicSkating;
use Illuminate\Http\Request;

class PublicSkatingController extends Controller
{
    public function index()
    {
        $skatings = PublicSkating::orderBy('date')->orderBy('time_from')->paginate(20);
        return view('admin.skating.index', compact('skatings'));
    }

    public function create()
    {
        return view('admin.skating.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'      => 'required|date',
            'time_from' => 'required|date_format:H:i',
            'time_to'   => 'required|date_format:H:i|after:time_from',
            'note'      => 'nullable|string|max:300',
            'is_active' => 'boolean',
        ], [
            'date.required'      => 'Zadejte datum.',
            'time_from.required' => 'Zadejte čas začátku.',
            'time_to.required'   => 'Zadejte čas konce.',
            'time_to.after'      => 'Čas konce musí být po čase začátku.',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        PublicSkating::create($data);

        return redirect()->route('admin.skating.index')->with('success', 'Termín byl přidán.');
    }

    public function edit(PublicSkating $skating)
    {
        return view('admin.skating.edit', compact('skating'));
    }

    public function update(Request $request, PublicSkating $skating)
    {
        $data = $request->validate([
            'date'      => 'required|date',
            'time_from' => 'required|date_format:H:i',
            'time_to'   => 'required|date_format:H:i|after:time_from',
            'note'      => 'nullable|string|max:300',
            'is_active' => 'boolean',
        ], [
            'date.required'      => 'Zadejte datum.',
            'time_from.required' => 'Zadejte čas začátku.',
            'time_to.required'   => 'Zadejte čas konce.',
            'time_to.after'      => 'Čas konce musí být po čase začátku.',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $skating->update($data);

        return redirect()->route('admin.skating.index')->with('success', 'Termín byl upraven.');
    }

    public function destroy(PublicSkating $skating)
    {
        $skating->delete();
        return back()->with('success', 'Termín byl smazán.');
    }
}
