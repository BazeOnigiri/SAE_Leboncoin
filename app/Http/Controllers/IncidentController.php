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

    public function index()
    {
        $allIncidents = Incident::with(['dateRecord', 'user', 'reservation'])->orderBy('idincident', 'desc')->get();

        $incidentsEnCours = $allIncidents->where('estclasse', false)->where('estremisaucontentieux', false);

        $incidentsContentieux = $allIncidents->where('estremisaucontentieux', true);

        $incidentsClasses = $allIncidents->where('estclasse', true)->where('estremisaucontentieux', false);

        return view('services.incidents.index', compact('incidentsEnCours', 'incidentsClasses', 'incidentsContentieux'));
    }

    public function classerSansSuite(Incident $incident)
    {
        $incident->update([
            'estclasse' => true,
        ]);

        return back()->with('success', 'L\'incident a été classé sans suite avec succès.');
    }

    public function validerVersEtape2(Incident $incident)
    {
        if ($incident->estclasse) {
            return back()->with('error', 'Impossible de valider un incident déjà classé.');
        }

        $incident->update(['etape' => 2]);
        return back()->with('success', 'Demande d\'explications envoyée au propriétaire.');
    }

    public function justificationForm(Reservation $reservation)
    {
        $incident = $reservation->incident;

        if (!$incident || $incident->etape != 2) {
            return redirect()->route('dashboard')->with('error', 'Cet incident ne nécessite pas de justification actuellement.');
        }

        if (Auth::id() !== $reservation->annonce->idutilisateur) {
            abort(403, 'Vous n\'êtes pas autorisé à répondre à cet incident.');
        }

        return view('profile.incidents.justification', compact('reservation', 'incident'));
    }

    public function storeJustification(Request $request, Reservation $reservation)
    {
        $request->validate([
            'explication' => 'required|string|min:10|max:5000',
        ]);

        $incident = $reservation->incident;

        if (Auth::id() !== $reservation->annonce->idutilisateur || $incident->etape != 2) {
            abort(403);
        }

        $incident->update([
            'explicationproprietaire' => $request->explication,
            'etape' => 3
        ]);

        return redirect()->route('user.annonces')
            ->with('success', 'Votre explication a été transmise avec succès au service de modération.');
    }

    public function rembourser(Incident $incident)
    {
        $incident->update([
            'etape' => 4,
            'estrembourse' => true,
            'estclasse' => true
        ]);

        return back()->with('success', 'Le processus de remboursement a été enclenché et l\'incident est maintenant clos.');
    }

    public function validerVersEtape4(Incident $incident)
    {
        if ($incident->estclasse || $incident->etape != 3) {
            return back()->with('error', 'Action impossible pour cet incident.');
        }

        $incident->update([
            'etape' => 4
        ]);

        return back()->with('success', 'Réponse transmise au locataire. Le remboursement a été refusé.');
    }

    public function suivi(Incident $incident)
    {
        $incident->load(['reservation.annonce', 'user']);
        
        return view('profile.incidents.suivi', compact('incident'));
    }

    public function contester(Incident $incident) // On injecte l'incident directement
    {
        if ($incident->idutilisateur != Auth::id()) {
            abort(403, "Vous n'êtes pas l'auteur de ce signalement.");
        }

        if ($incident->etape != 4) {
            return redirect()->back()->with('error', "La contestation n'est possible qu'après une décision (Étape 4).");
        }

        $incident->update([
            'etape' => 5,
            'estremisaucontentieux' => false 
        ]);

        return redirect()->route('user.incidents.index')
            ->with('success', 'Votre contestation a été transmise au service client pour une dernière expertise.');
    }

    public function accepter(Incident $incident)
    {
        if ($incident->idutilisateur != Auth::id()) {
            abort(403);
        }

        $incident->update([
            'etape' => 5,
            'estclasse' => true
        ]);

        return redirect()->route('user.incidents.index')
            ->with('success', 'Le litige est désormais clôturé. Merci de votre retour.');
    }

    public function cloturer(Reservation $reservation)
    {
        $incident = $reservation->incident;

        if ($incident->etape < 4) abort(403);

        $incident->update(['etape' => 6, 'estclasse' => true]);

        return redirect()->route('user.mes-reservations')->with('success', 'L\'incident est désormais clos.');
    }

    public function envoyerAuContentieux(Incident $incident)
    {
        $incident->update([
            'estremisaucontentieux' => true,
            'etape' => 6, 
        ]);

        return back()->with('success', 'Dossier transmis au contentieux.');
    }

    public function userIncidents()
    {
        // On récupère tous les incidents de l'utilisateur avec les infos de l'annonce
        $incidents = Incident::where('idutilisateur', Auth::id())
            ->with(['reservation.annonce'])
            ->orderBy('iddate', 'desc')
            ->get();

        return view('profile.incidents.index', compact('incidents'));
    }
}