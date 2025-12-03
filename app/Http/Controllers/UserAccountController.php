<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Annonce;
use App\Models\Date as DateModel;
use App\Models\Ville;
use App\Models\Adresse;
use App\Models\Particulier;

class UserAccountController extends Controller
{
    public function edit()
    {
        return view('user-account.edit', ['user' => Auth::user()]);
    }

    public function annonces()
    {
        $user = Auth::user();
        $annonces = Annonce::where('idutilisateur', $user->idutilisateur)
            ->with(['photos', 'typeHebergement', 'adresse.ville']) 
            ->orderBy('idannonce', 'desc')
            ->get();

        return view('user-account.annonces', compact('annonces'));
    }

    public function spaces()
    {
        return view('user-account.spaces');
    }

    public function security()
    {
        return view('user-account.security');
    }
    
    public function settings()
    {
        $user = Auth::user();
        $user->load(['particulier.dateNaissance', 'adresse.ville']);
        return view('user-account.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'civilite' => 'required|in:Monsieur,Madame',
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'date_naissance' => 'required|date',
            'telephone' => 'nullable|regex:/^0[1-9][0-9]{8}$/',
            'numerorue' => 'nullable|integer',
            'nomrue' => 'required|string|max:39',
            'codepostal' => 'required|string|size:5',
            'nomville' => 'required|string|max:40',
        ]);

        // Mise à jour table Utilisateur
        $user->nomutilisateur = $validated['nom'];
        $user->prenomutilisateur = $validated['prenom'];
        $user->telephoneutilisateur = $validated['telephone'];
        $user->save();

        // Mise à jour table Particulier
        $dateModel = DateModel::firstOrCreate(['date' => $validated['date_naissance']]);
        
        $particulier = $user->particulier;
        if (!$particulier) {
            $particulier = new Particulier();
            $particulier->idutilisateur = $user->idutilisateur;
        }
        $particulier->civilite = $validated['civilite'];
        $particulier->iddate = $dateModel->iddate;
        $particulier->save();

        // Mise à jour Adresse et Ville
        $ville = Ville::firstOrCreate(
            ['codepostal' => $validated['codepostal'], 'nomville' => strtoupper($validated['nomville'])],
            ['iddepartement' => 1, 'taxedesejour' => 0] 
        );

        // Mise à jour de l'adresse liée
        $adresse = $user->adresse;
        if (!$adresse) {
            $adresse = new Adresse();
        }
        $adresse->numerorue = $validated['numerorue'];
        $adresse->nomrue = $validated['nomrue'];
        $adresse->idville = $ville->idville;
        $adresse->save();

        // Lier la nouvelle adresse si c'était une création
        if ($user->idadresse !== $adresse->idadresse) {
            $user->idadresse = $adresse->idadresse;
            $user->save();
        }

        return redirect()->route('user.settings')->with('status', 'Profil mis à jour avec succès !');
    }
}