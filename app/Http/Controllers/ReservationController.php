<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function mesReservations()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $allReservations = Reservation::where('idutilisateur', Auth::id())
            ->with(['annonce.photos', 'annonce.adresse.ville', 'dateDebut', 'dateFin', 'transaction']) 
            ->orderBy('iddatedebutreservation', 'desc')
            ->get();

        $reservationsAVenir = $allReservations->filter(function ($value) {
            return $value->est_passee == false;
        });

        $reservationsPassees = $allReservations->filter(function ($value) {
            return $value->est_passee == true;
        });
        
        return view('user-account.reservations', compact('reservationsAVenir', 'reservationsPassees'));
    }
    public function create(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        $arrivee = $request->query('arrivee');
        $depart = $request->query('depart');
        return view('reservation-create', compact('annonce', 'arrivee', 'depart'));
    }
}