<?php

namespace App\Http\Controllers;

use App\Mail\AnnonceRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    public function reject($idutilisateur)
    {
        $user = User::findOrFail($idutilisateur);

        $cniPath = 'cni/' . $user->idutilisateur;
        if (Storage::disk('local')->exists($cniPath)) {
            Storage::disk('local')->deleteDirectory($cniPath);
        }

        Mail::to($user->email)->send(new AnnonceRejectedMail($user));

        return redirect()->route('services-petites-annonces.index')->with('success', 'La demande de vérification a été rejetée et l\'utilisateur a été notifié par email.');
    }
}
