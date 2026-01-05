<?php

namespace App\Http\Controllers;

use App\Mail\AnnonceRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Commodite;
use App\Models\Categorie;

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

    public function catalogueIndex()
    {
        return view('services.catalogue.index');
    }

    public function storeEquipement(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:50|unique:commodite,nomcommodite']);

        // On cherche "Équipement" avec l'accent exact du script
        $idCategorie = \DB::table('categorie')
            ->where('nomcategorie', 'Équipement')
            ->value('idcategorie');

        if (!$idCategorie) {
            // Plan B : recherche plus souple si l'accent pose problème à l'encodage
            $idCategorie = \DB::table('categorie')
                ->where('nomcategorie', 'ILIKE', '%quipement%')
                ->value('idcategorie');
        }

        if (!$idCategorie) {
            return back()->with('error', 'La catégorie "Équipement" n\'est pas reconnue.');
        }

        \App\Models\Commodite::create([
            'nomcommodite' => $request->nom,
            'idcategorie'  => $idCategorie
        ]);

        return back()->with('success', "Équipement ajouté.");
    }

    public function storeHebergement(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:50|unique:commodite,nomcommodite']);

        // On cherche "Hébergement" avec l'accent exact du script
        $idCategorie = \DB::table('categorie')
            ->where('nomcategorie', 'Hébergement')
            ->value('idcategorie');

        if (!$idCategorie) {
            // Plan B : recherche sans l'accent initial
            $idCategorie = \DB::table('categorie')
                ->where('nomcategorie', 'ILIKE', '%bergement%')
                ->value('idcategorie');
        }

        if (!$idCategorie) {
            return back()->with('error', 'La catégorie "Hébergement" n\'est pas reconnue.');
        }

        \App\Models\Commodite::create([
            'nomcommodite' => $request->nom,
            'idcategorie'  => $idCategorie
        ]);

        \App\Models\TypeHebergement::create([
            'nomtypehebergement' => $request->nom,
            'idcategorie'        => $idCategorie
        ]);

        return back()->with('success', "Type d'hébergement ajouté.");
    }
}

