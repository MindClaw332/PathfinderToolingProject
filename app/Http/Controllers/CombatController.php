<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creature;
use Barryvdh\DomPDF\Facade\Pdf; // Add DOMPDF facade
use Illuminate\Routing\Controller;


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
    
    // Add PDF generation method
    public function generatePDF(Request $request)
    {
        // Validate the request
        $request->validate([
            'combatants' => 'required|json'
        ]);
        
        // Decode combatants JSON
        $combatants = json_decode($request->combatants, true);
        
        // Generate PDF
        $pdf = Pdf::loadView('combat-pdf', compact('combatants'))
            ->setPaper('a4', 'landscape');
            
        return $pdf->download('combat-summary.pdf');
    }
}