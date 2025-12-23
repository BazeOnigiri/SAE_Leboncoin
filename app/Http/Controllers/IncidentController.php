<?php

namespace App\Http\Controllers;

use App\Models\Compensation;
use App\Models\Incident;
use App\Models\Reservation; 
use App\Models\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncidentController extends Controller
{
    public function create(Reservation $reservation)
    {
        $compensations = Compensation::all();
        return view('profile.incidents.create', compact('reservation', 'compensations'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        $request->validate([
            'motif' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:2000'],
            'compensations' => ['nullable', 'array'],
            'compensations.*' => ['exists:compensation,idcompensation'], 
        ]);

        $today = Carbon::now()->format('Y-m-d'); 
        
        $dateRecord = Date::where('date', $today)->first();

        if (!$dateRecord) {
            return back()->withInput()->withErrors(['date' => 'La date du jour est manquante dans la table de référence. Veuillez contacter l\'administrateur.']);
        }
        
        $idDate = $dateRecord->iddate;
        
        $incident = Incident::create([
            'idutilisateur' => Auth::id(), 
            'idreservation' => $reservation->idreservation,
            'iddate' => $idDate,
            'motifincident' => $request->motif,
            'descriptionincident' => $request->description,
        ]);

        if ($request->has('compensations')) {
            $incident->compensationsDemandees()->attach($request->compensations);
        }

        return redirect()->route('user.mes-reservations')
                         ->with('success', 'Votre incident a été signalé avec succès. Vous pouvez le suivre depuis votre page de réservations.');
    }
}