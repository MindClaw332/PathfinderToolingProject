<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creature;
use Illuminate\Routing\Controller;

class CreatureController extends Controller
{
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