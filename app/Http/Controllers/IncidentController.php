<?php

namespace App\Http\Controllers;

use App\Models\Compensation;
use App\Models\Incident;
use App\Models\Reservation; 
use App\Models\Date; // ⬅️ IMPORTANT : L'importation du modèle Date
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncidentController extends Controller
{
    /**
     * Affiche le formulaire de signalement pour une réservation donnée.
     */
    public function create(Reservation $reservation)
    {
        // Récupère toutes les options de compensation pour les afficher dans le formulaire
        $compensations = Compensation::all();

        // Retourne la vue, en utilisant le chemin ajusté 'profile.incidents.create'
        return view('profile.incidents.create', compact('reservation', 'compensations'));
    }

    /**
     * Traite la soumission du formulaire et crée l'incident.
     */
    public function store(Request $request, Reservation $reservation)
    {
        // 1. Validation des données
        $request->validate([
            'motif' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:2000'],
            'compensations' => ['nullable', 'array'],
            // Vérifie que les IDs de compensation existent dans la table 'compensation'
            'compensations.*' => ['exists:compensation,idcompensation'], 
        ]);

        // 2. Gestion de l'iddate (Recherche dans la table de dimension)
        
        // Obtenir la date du jour au format YYYY-MM-DD
        $today = Carbon::now()->format('Y-m-d'); 
        
        // Rechercher l'ID de la date dans la table 'date'
        $dateRecord = Date::where('date', $today)->first();

        if (!$dateRecord) {
            // Sécurité si la date du jour n'est pas dans la table de référence
            return back()->withInput()->withErrors(['date' => 'La date du jour est manquante dans la table de référence. Veuillez contacter l\'administrateur.']);
        }
        
        $idDate = $dateRecord->iddate;
        
        // 3. Création de l'incident
        $incident = Incident::create([
            'idutilisateur' => Auth::id(), 
            'idreservation' => $reservation->idreservation,
            'iddate' => $idDate, // Clé étrangère vers la date du signalement
            'motifincident' => $request->motif,
            'descriptionincident' => $request->description,
        ]);

        // 4. Liaison avec les compensations (Insertion dans la table 'demander')
        if ($request->has('compensations')) {
            $incident->compensationsDemandees()->attach($request->compensations);
        }

        // 5. Redirection corrigée vers la liste des réservations
        // ----------------------------------------------------------------------------------
        return redirect()->route('user.mes-reservations') // ⬅️ CORRECTION APPORTÉE ICI
                         ->with('success', 'Votre incident a été signalé avec succès. Vous pouvez le suivre depuis votre page de réservations.');
        // ----------------------------------------------------------------------------------
    }
}