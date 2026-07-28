<?php

namespace App\Http\Controllers;

use App\Models\Team;

class TeamController extends Controller
{
    public function show(Team $team)
    {
        abort_unless($team->is_active, 404);

        $players = $team->activePlayers()->get();

        $playersByPosition = collect([
            'Brankáři' => $players->where('position', 'Brankář')->values(),
            'Obránci'  => $players->where('position', 'Obránce')->values(),
            'Útočníci' => $players->where('position', 'Útočník')->values(),
        ])->filter(fn($g) => $g->isNotEmpty());

        // Hráči bez pozice — přidáme na konec pokud existují
        $withoutPosition = $players->whereNotIn('position', ['Brankář', 'Obránce', 'Útočník'])->values();
        if ($withoutPosition->isNotEmpty()) {
            $playersByPosition->put('Ostatní', $withoutPosition);
        }

        $teams = Team::active()->get();

        return view('teams.show', compact('team', 'players', 'playersByPosition', 'teams'));
    }
}
