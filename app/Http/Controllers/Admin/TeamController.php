<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('sort_order')->get();
        return view('admin.teams.index', compact('teams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_cs'        => 'required|string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'age_group'      => 'nullable|string|max:20',
            'description_cs' => 'nullable|string',
            'description_en' => 'nullable|string',
            'color'          => 'required|string|size:7',
        ]);

        $base = Str::slug($data['name_cs']);
        $slug = $base;
        $i = 1;
        while (Team::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug'] = $slug;

        // Nový tým se vždy zařadí na konec; pořadí se dál mění jen přetažením v seznamu.
        $data['sort_order'] = (int) Team::max('sort_order') + 1;

        Team::create($data);
        return redirect()->route('admin.teams.index')->with('success', 'Tým byl přidán.');
    }

    public function update(Request $request, Team $team)
    {
        $data = $request->validate([
            'name_cs'        => 'required|string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'age_group'      => 'nullable|string|max:20',
            'description_cs' => 'nullable|string',
            'description_en' => 'nullable|string',
            'color'          => 'required|string|size:7',
            'is_active'      => 'boolean',
        ]);

        // sort_order se neupravuje formulářem, jen přetažením v seznamu.
        $data['is_active'] = $request->boolean('is_active', true);
        $team->update($data);
        return redirect()->route('admin.teams.index')->with('success', 'Tým byl uložen.');
    }

    public function destroy(Team $team)
    {
        if ($team->players()->count() > 0) {
            return redirect()->route('admin.teams.index')
                ->with('error', 'Tým nelze smazat – obsahuje ' . $team->players()->count() . ' hráčů.');
        }
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Tým byl smazán.');
    }

    /** Přeuspořádat týmy podle pořadí ID zaslaného z drag & drop seznamu. */
    public function reorder(Request $request)
    {
        $data = $request->validate([
            'team_ids'   => 'required|array|min:1',
            'team_ids.*' => 'exists:teams,id',
        ]);

        // Endpoint očekává kompletní seznam ID všech týmů (bez stránkování),
        // jinak by zbylé týmy měly neplatné/neúplné pořadí.
        if (count($data['team_ids']) !== Team::count()) {
            return response()->json(['ok' => false, 'error' => 'Seznam musí obsahovat všechny týmy.'], 422);
        }

        DB::transaction(function () use ($data) {
            // sort_order je unsignedTinyInteger (0–255); dočasný posun proto
            // musí zůstat v bezpečném rozsahu, aby nedošlo k přetečení.
            $count  = count($data['team_ids']);
            $offset = $count; // max. konečná hodnota po posunu: 2*count - 1, v bezpečí pod 255 pro běžný počet týmů
            foreach ($data['team_ids'] as $index => $id) {
                Team::where('id', $id)->update(['sort_order' => $offset + $index]);
            }
            foreach ($data['team_ids'] as $index => $id) {
                Team::where('id', $id)->update(['sort_order' => $index]);
            }
        });

        return response()->json(['ok' => true]);
    }
}
