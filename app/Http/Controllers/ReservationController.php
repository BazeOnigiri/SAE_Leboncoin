<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Annonce;
use App\Models\Message;
use App\Models\Date;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function store(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required',
            'date_fin' => 'required',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'babies' => 'nullable|integer|min:0',
            'prenomutilisateur' => 'required|string|max:50',
            'nomutilisateur' => 'required|string|max:50',
            'telephoneutilisateur' => 'nullable|string|max:10',
            'message' => 'nullable|string|max:2500',
        ]);

        $annonce = Annonce::findOrFail($id);

        $dateDebut = Carbon::createFromFormat('d/m/Y', $request->date_debut);
        $dateFin = Carbon::createFromFormat('d/m/Y', $request->date_fin);

        $dateDebutEntry = Date::firstOrCreate(
            ['date' => $dateDebut->format('Y-m-d')]
        );
        
        $dateFinEntry = Date::firstOrCreate(
            ['date' => $dateFin->format('Y-m-d')]
        );

        $reservation = new Reservation();
        $reservation->idannonce = $annonce->idannonce;
        $reservation->idutilisateur = Auth::id();
        $reservation->iddatedebutreservation = $dateDebutEntry->iddate;
        $reservation->iddatefinreservation = $dateFinEntry->iddate;
        $reservation->nomclient = $request->nomutilisateur;
        $reservation->prenomclient = $request->prenomutilisateur;
        $reservation->telephoneclient = $request->telephoneutilisateur;
        $reservation->save();

        if ($request->filled('message')) {
            $today = Carbon::now();
            $dateEntry = Date::firstOrCreate(
                ['date' => $today->format('Y-m-d')]
            );

            Message::create([
                'idutilisateurexpediteur' => Auth::id(),
                'idutilisateurreceveur' => $annonce->idutilisateur,
                'iddate' => $dateEntry->iddate,
                'contenumessage' => $request->message,
                'idreservation' => $reservation->idreservation,
                'lu' => false,
                'created_at' => $today,
            ]);
        }

        return redirect()->route('user.mes-reservations')
            ->with('success', 'Votre demande de réservation a été envoyée avec succès !');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->idutilisateur !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler cette réservation.');
        }

        if ($reservation->transaction) {
            $reservation->transaction->delete();
        }

        $reservation->delete();

        return redirect()->route('user.mes-reservations')
            ->with('success', 'La réservation a été annulée avec succès.');
    }
}