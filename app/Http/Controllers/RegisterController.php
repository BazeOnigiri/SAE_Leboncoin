<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Particulier;
use App\Models\Professionnel;
use App\Models\Adresse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    // afficher le formulaire email + mot de passe
     
    public function showEmailForm()
    {
        // Vue: resources/views/auth/register.blade.php
        return view('auth.register');
    }


    // traiter email + mot de passe

    public function storeEmail(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email|unique:utilisateur,email',
            'password' => 'required|confirmed|min:8',
        ]);

        // On garde les infos en session pour les étapes suivantes
        session([
            'register.email'    => $data['email'],
            'register.password' => $data['password'],
        ]);

        return redirect()->route('register.type');
    }

    // choix du type de compte
    public function showTypeForm()
    {
        // Vue: resources/views/auth/register-type.blade.php
        return view('auth.register-type');
    }

    public function storeType(Request $request)
    {
        $data = $request->validate([
            'type_compte' => 'required|in:particulier,professionnel',
        ]);

        session(['register.type' => $data['type_compte']]);

        return $data['type_compte'] === 'particulier'
            ? redirect()->route('register.particulier')
            : redirect()->route('register.pro');
    }

    // formulaire particulier
    public function showParticulierForm()
    {
        // Vue: resources/views/auth/register-particulier.blade.php
        return view('auth.register-particulier');
    }

    public function storeParticulier(Request $request)
    {
        $data = $request->validate([
            'civilite'   => 'required',
            'nom'        => 'required',
            'prenom'     => 'required',
            'telephone'  => 'nullable',
            'numerorue'  => 'required|integer',
            'nomrue'     => 'required',
            'idville'    => 'required|integer', 
        ]);

        // Crée l'utilisateur + l'adresse
        $user = $this->createUserWithAdresse($data);

        // Crée la ligne dans la table particulier (sans ::create)
        $particulier = new Particulier();
        $particulier->idutilisateur = $user->idutilisateur;
        $particulier->civilite      = $data['civilite'];
        $particulier->iddate        = 1; 
        $particulier->save();

        // Connexion automatique
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // formulaire professionnel
    public function showProForm()
    {
        // Vue: resources/views/auth/register-pro.blade.php
        return view('auth.register-pro');
    }

    public function storePro(Request $request)
    {
        $data = $request->validate([
            'nom'        => 'required',
            'prenom'     => 'required',
            'telephone'  => 'nullable',
            'numerorue'  => 'required|integer',
            'nomrue'     => 'required',
            'idville'    => 'required|integer',
            'numsiret'   => 'required',
            'nomsociete' => 'required',
            'secteuractivite' => 'required',
        ]);

        // Crée l'utilisateur + l'adresse
        $user = $this->createUserWithAdresse($data);

        // Crée la ligne dans la table professionnel (sans ::create)
        $pro = new Professionnel();
        $pro->idutilisateur   = $user->idutilisateur;
        $pro->numsiret        = $data['numsiret'];
        $pro->nomsociete      = $data['nomsociete'];
        $pro->secteuractivite = $data['secteuractivite'];
        $pro->save();

        // Connexion automatique
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function createUserWithAdresse(array $data): User
    {
        // 1) Création de l'adresse
        $adresse = new Adresse();
        $adresse->idville   = $data['idville'];
        $adresse->numerorue = $data['numerorue'];
        $adresse->nomrue    = $data['nomrue'];
        $adresse->save();

        // 2) Création de l'utilisateur
        $user = new User();
        $user->idadresse            = $adresse->idadresse;
        $user->nomutilisateur       = $data['nom'];
        $user->prenomutilisateur    = $data['prenom'];
        $user->pseudonyme           = $data['prenom'] . '_' . substr($data['nom'], 0, 3);
        $user->email                = session('register.email');
        $user->telephoneutilisateur = $data['telephone'] ?? null;
        $user->solde                = 0;
        $user->password             = Hash::make(session('register.password'));
        $user->save();

        return $user;
    }
}
