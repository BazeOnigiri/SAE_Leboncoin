<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

use App\Models\Annonce;
use App\Models\Date as DateModel;
use App\Models\Ville;
use App\Models\Adresse;
use App\Models\Particulier;
use App\Models\Region;
use App\Models\Departement;

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
            'civilite' => ['required', 'in:Monsieur,Madame,Non spécifié'],

            'nom'      => ['required', 'string', 'max:50'],
            'prenom'   => ['required', 'string', 'max:50'],

            'email' => [
                'required',
                'string',
                'email',
                'max:320',
                Rule::unique('utilisateur', 'email')->ignore($user->idutilisateur, 'idutilisateur'),
            ],

            'date_naissance' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
            ],

            'telephone' => [
                'required',
                'regex:/^0[1-9][0-9]{8}$/',
            ],

            'numerorue'  => ['nullable', 'integer', 'min:1', 'max:99999'],
            'nomrue'     => ['required', 'string', 'max:39'],
            'codepostal' => ['required', 'string', 'size:5', 'regex:/^[0-9]{5}$/'],
            'nomville'   => ['required', 'string', 'max:40'],
        ]);

        $user->nomutilisateur       = $validated['nom'];
        $user->prenomutilisateur    = $validated['prenom'];
        $user->telephoneutilisateur = $validated['telephone'];
        $user->email                = $validated['email'];
        $user->save();

        $dateModel = DateModel::firstOrCreate(['date' => $validated['date_naissance']]);

        $particulier = $user->particulier;
        if (!$particulier) {
            $particulier = new Particulier();
            $particulier->idutilisateur = $user->idutilisateur;
        }
        $particulier->civilite = $validated['civilite'];
        $particulier->iddate   = $dateModel->iddate;
        $particulier->save();

        $nomVilleSaisi = strtoupper($validated['nomville']);
        $codePostal    = $validated['codepostal'];

        $ville = Ville::where('nomville', $nomVilleSaisi)
            ->where('codepostal', $codePostal)
            ->first();

        if (!$ville) {
            $communeData = null;

            try {
                $http = Http::timeout(5);

                if (app()->environment('local')) {
                    $http = $http->withoutVerifying();
                }

                $response = $http->get('https://geo.api.gouv.fr/communes', [
                    'codePostal' => $codePostal,
                    'fields'     => 'nom,code,codesPostaux,codeDepartement,codeRegion,departement,region',
                    'format'     => 'json',
                ]);

                if ($response->successful()) {
                    $communes = $response->json();

                    foreach ($communes as $commune) {
                        if (strtoupper($commune['nom']) === $nomVilleSaisi) {
                            $communeData = $commune;
                            break;
                        }
                    }

                    if (!$communeData && !empty($communes)) {
                        $communeData = $communes[0];
                    }
                }
            } catch (\Throwable $e) {
                $communeData = null;
            }

            if (!$communeData) {
                $region = Region::firstOrCreate(
                    ['nomregion' => 'INCONNUE'],
                    []
                );

                $depNumero = substr($codePostal, 0, 2);
                if (in_array($depNumero, ['97', '98'])) {
                    $depNumero = substr($codePostal, 0, 3);
                }

                $departement = Departement::firstOrCreate(
                    ['numerodepartement' => $depNumero],
                    [
                        'idregion'       => $region->idregion,
                        'nomdepartement' => 'DEPARTEMENT ' . $depNumero,
                    ]
                );

                $taxe = random_int(50, 300) / 100;

                $ville = new Ville();
                $ville->iddepartement = $departement->iddepartement;
                $ville->codepostal    = $codePostal;
                $ville->nomville      = $nomVilleSaisi;
                $ville->taxedesejour  = $taxe;
                $ville->save();
            } else {
                $nomCommuneApi      = strtoupper($communeData['nom']);
                $codeDepartementApi = $communeData['codeDepartement'] ?? null;
                $codeRegionApi      = $communeData['codeRegion'] ?? null;

                $region = Region::firstOrCreate(
                    ['nomregion' => 'REGION ' . $codeRegionApi],
                    []
                );

                $departement = Departement::firstOrCreate(
                    ['numerodepartement' => $codeDepartementApi],
                    [
                        'idregion'       => $region->idregion,
                        'nomdepartement' => 'DEPARTEMENT ' . $codeDepartementApi,
                    ]
                );

                $taxe = random_int(50, 300) / 100;

                $ville = new Ville();
                $ville->iddepartement = $departement->iddepartement;
                $ville->codepostal    = $codePostal;
                $ville->nomville      = $nomCommuneApi;
                $ville->taxedesejour  = $taxe;
                $ville->save();
            }
        }

        $adresse = Adresse::firstOrCreate([
            'numerorue' => $validated['numerorue'],
            'nomrue'    => $validated['nomrue'],
            'idville'   => $ville->idville,
        ]);

        if ($user->idadresse !== $adresse->idadresse) {
            $user->idadresse = $adresse->idadresse;
            $user->save();
        }

        return redirect()
            ->route('user.settings')
            ->with('status', 'Profil mis à jour avec succès !');
    }
}