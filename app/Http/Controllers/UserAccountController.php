<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Annonce;

class UserAccountController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('user-account.edit', compact('user'));
    }

    public function annonces()
    {
        $user = Auth::user();
        $annonces = Annonce::where('idutilisateur', $user->idutilisateur)
            ->with('photos', 'typeHebergement', 'adresse.ville')
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
        $user->load('particulier', 'adresse.ville');
        return view('user-account.settings', compact('user'));
    }
}