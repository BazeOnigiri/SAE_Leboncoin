<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CNIController extends Controller
{
    public function index() {
        if (auth()->user()->isCNIValidate()) {
            return redirect()->route('dashboard')->with('info', 'Votre CNI est déjà vérifiée.');
        }
        return view("cni.index");
    }

    public function store(Request $request) {
        if (auth()->user()->isCNIValidate()) {
            return redirect()->route('dashboard')->with('info', 'Votre CNI est déjà vérifiée.');
        }
        $validated = $request->validate([
            'recto' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
            'verso' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $idutilisateur = auth()->id();
        if (!$idutilisateur) {
            return redirect()->back()->withErrors(['error' => 'Aucun ID utilisateur trouvé. Veuillez vous connecter.']);
        }

        $rectoPath = $request->file('recto')->store('cni/' . $idutilisateur . '/recto', 'local');
        $versoPath = $request->file('verso')->store('cni/' . $idutilisateur . '/verso', 'local');

        return redirect()->route('dashboard')->with([
            'success' => 'CNI traité avec succès.',
        ]);
    }
}
