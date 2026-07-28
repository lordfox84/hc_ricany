<?php

namespace App\Http\Controllers;

use App\Models\Team;

class AboutController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('sort_order')->get();
        return view('about', compact('teams'));
    }
}
