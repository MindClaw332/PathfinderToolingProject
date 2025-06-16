<?php

namespace App\Http\Controllers;

use App\Models\Creature;

class CombatController extends Controller
{
public function index()
{
    $creatures = Creature::select([
            'id',
            'name',
            'level',
            'ac',
            'hp',
            'fortitude',
            'reflex',
            'will',
            'perception',
            'speed'
        ])
        ->orderBy('name')
        ->get();

    return view('combat', compact('creatures'));
}

public function search(Request $request)
{
    $query = $request->input('query', '');
    
    $creatures = Creature::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', '%' . $query . '%');
        })
        ->orderBy('name')
        ->limit(50)
        ->get();
        
    return response()->json($creatures);
}
}