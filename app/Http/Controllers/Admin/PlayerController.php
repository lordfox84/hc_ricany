<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $teams   = Team::orderBy('sort_order')->get();
        $players = Player::with('team')->orderBy('team_id')->orderBy('sort_order')->orderBy('last_name')->get();
        return view('admin.players.index', compact('teams', 'players'));
    }

    public function create()
    {
        $teams = Team::orderBy('sort_order')->get();
        return view('admin.players.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'team_id'       => 'required|exists:teams,id',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'jersey_number' => 'nullable|integer|min:0|max:99',
            'position'      => 'nullable|string|max:50',
            'hand'          => 'nullable|in:Levák,Pravák',
            'height_cm'     => 'nullable|integer|min:100|max:230',
            'weight_kg'     => 'nullable|integer|min:20|max:200',
            'photo'         => 'nullable|image|max:4096',
            'date_of_birth' => 'nullable|date',
            'bio'           => 'nullable|string',
            'sort_order'    => 'integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('players', 'public');
        }

        $data['name']      = trim($data['first_name'] . ' ' . $data['last_name']);
        $data['is_active'] = true;
        Player::create($data);
        return redirect()->route('admin.players.index')->with('success', 'Hráč byl přidán.');
    }

    public function edit(Player $player)
    {
        $teams = Team::orderBy('sort_order')->get();
        return view('admin.players.edit', compact('player', 'teams'));
    }

    public function update(Request $request, Player $player)
    {
        $data = $request->validate([
            'team_id'       => 'required|exists:teams,id',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'jersey_number' => 'nullable|integer|min:0|max:99',
            'position'      => 'nullable|string|max:50',
            'hand'          => 'nullable|in:Levák,Pravák',
            'height_cm'     => 'nullable|integer|min:100|max:230',
            'weight_kg'     => 'nullable|integer|min:20|max:200',
            'photo'         => 'nullable|image|max:4096',
            'date_of_birth' => 'nullable|date',
            'bio'           => 'nullable|string',
            'sort_order'    => 'integer|min:0',
            'is_active'     => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('players', 'public');
        }

        $data['name']      = trim($data['first_name'] . ' ' . $data['last_name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $player->update($data);
        return redirect()->route('admin.players.index')->with('success', 'Hráč byl uložen.');
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('admin.players.index')->with('success', 'Hráč byl smazán.');
    }

    /** Hromadně přeřadit vybrané hráče do jiného týmu (např. přechod do nové sezóny). */
    public function bulkMoveTeam(Request $request)
    {
        $data = $request->validate([
            'player_ids'   => 'required|array|min:1',
            'player_ids.*' => 'exists:players,id',
            'new_team_id'  => 'required|exists:teams,id',
        ]);

        $count = Player::whereIn('id', $data['player_ids'])->update(['team_id' => $data['new_team_id']]);

        return redirect()->route('admin.players.index')
            ->with('success', "Přesunuto hráčů: {$count}.");
    }
}
