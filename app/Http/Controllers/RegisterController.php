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
    // afficher formulaire email + mot de passe
    public function showEmailForm()
    {
        return view('auth.register'); 
    }

    // traiter email + mot de passe
    public function storeEmail(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email|unique:utilisateur,email',
            'password' => 'required|confirmed|min:8',
        ]);

        // on garde les infos en session pour les étapes suivantes
        session([
            'register.email'    => $data['email'],
            'register.password' => $data['password'],
        ]);

        return redirect()->route('register.type');
    }

    // choix type de compte
    public function showTypeForm()
    {
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

        $user = $this->createUserWithAdresse($data);

        Particulier::create([
            'idutilisateur' => $user->idutilisateur,
            'civilite'      => $data['civilite'],
            'iddate'        => 1,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // formulaire professionnel
    public function showProForm()
    {
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

        $user = $this->createUserWithAdresse($data);

        Professionnel::create([
            'idutilisateur'   => $user->idutilisateur,
            'numsiret'        => $data['numsiret'],
            'nomsociete'      => $data['nomsociete'],
            'secteuractivite' => $data['secteuractivite'],
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function createUserWithAdresse(array $data): User
    {
        // création de l'adresse
        $adresse = Adresse::create([
            'idville'   => $data['idville'],
            'numerorue' => $data['numerorue'],
            'nomrue'    => $data['nomrue'],
        ]);

        // création de l'utilisateur
        $user = User::create([
            'idadresse'          => $adresse->idadresse,
            'nomutilisateur'     => $data['nom'],
            'prenomutilisateur'  => $data['prenom'],
            'pseudonyme'         => $data['prenom'] . '_' . substr($data['nom'], 0, 3),
            'email'              => session('register.email'),
            'telephoneutilisateur' => $data['telephone'] ?? null,
            'solde'              => 0,
            'password'           => Hash::make(session('register.password')),
        ]);

        return $user;
    }
}
