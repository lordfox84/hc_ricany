<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\Team;
use Illuminate\Http\Request;

class CampController extends Controller
{
    public function index(Request $request)
    {
        $typ = $request->query('typ');

        $query = Camp::published();

        if (in_array($typ, ['letni', 'skills'])) {
            $query->where('type', $typ);
        }

        $camps = $query->get();
        $teams = Team::active()->get();

        $typeLabel = match($typ) {
            'letni'  => 'Letní kempy',
            'skills' => 'Skills kempy',
            default  => 'Hokejové kempy',
        };

        return view('camps.index', compact('camps', 'teams', 'typeLabel'));
    }
}
