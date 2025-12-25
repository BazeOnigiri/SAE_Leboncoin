<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CNIController extends Controller
{
    public function index() {
        if (auth()->user()->hasCniFiles() || auth()->user()->identiteEstVerifie()) {
            return redirect()->route('dashboard')->with('info', 'Vous avez déjà fait une demande de vérification de votre CNI ou elle est déjà vérifiée.');
        }
        return view("cni.index");
    }

    public function store(Request $request) {
        if (auth()->user()->identiteEstVerifie()) {
            return redirect()->route('dashboard')->with('info', 'Votre CNI est déjà vérifiée.');
        }
        $validated = $request->validate([
            'recto' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
            'verso' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $idutilisateur = auth()->id();

        $rectoExt = $request->file('recto')->getClientOriginalExtension();
        $versoExt = $request->file('verso')->getClientOriginalExtension();

        $request->file('recto')->storeAs(
            "cni/{$idutilisateur}/recto",
            "recto.$rectoExt",
            'local'
        );

        $request->file('verso')->storeAs(
            "cni/{$idutilisateur}/verso",
            "verso.$versoExt",
            'local'
        );

        return redirect()->route('dashboard')->with([
            'success' => 'CNI traité avec succès.',
        ]);
    }
}
