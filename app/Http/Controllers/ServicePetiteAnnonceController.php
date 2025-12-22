<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ServicePetiteAnnonceController extends Controller
{
    public function index()
    {
        $users = User::where('identity_verified', false)
            ->get()
            ->filter(fn (User $user) => $user->hasCniFiles());

        return view('services.petites-annonces', compact('users'));
    }

    public function verify($idutilisateur)
    {
        $user = User::findOrFail($idutilisateur);

        if ($user->identity_verified) {
            return redirect()->route('service.petite-annonce.index')->with('info', 'L\'utilisateur a déjà une identité vérifiée.');
        }

        $user->identity_verified = true;
        $user->save();

        return redirect()->route('services-petites-annonces.index')->with('success', 'L\'identité de l\'utilisateur a été vérifiée avec succès.');
    }
}
