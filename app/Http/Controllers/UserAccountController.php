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
use App\Models\Professionnel;
use App\Models\Region;
use App\Models\Departement;

class UserAccountController extends Controller
{
    // Liste des secteurs basée sur inserts.sql
    private const SECTEURS = [
        'Agroalimentaire',
        'Banque / Assurance',
        'BTP / Matériaux de construction',
        'Chimie / Parachimie',
        'Commerce / Négoce / Distribution',
        'Édition / Communication / Multimédia',
        'Énergie / Environnement',
        'Industrie pharmaceutique',
        'Informatique / Télécoms',
        'Machines et équipements / Automobile',
        'Services aux entreprises',
        'Textile / Habillement / Chaussure',
        'Tourisme - Restauration',
        'Transports / Logistique',
        'Autre secteur',
    ];

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

    public function favorites()
    {
        $favorites = Auth::user()->annoncesFavorisees()
            ->with(['photos', 'typeHebergement', 'adresse.ville'])
            ->orderBy('idannonce', 'desc')
            ->get();

        return view('user-account.favorites', compact('favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate(['idannonce' => 'required|integer|exists:annonce,idannonce']);
        
        $user = Auth::user();
        $idAnnonce = $request->idannonce;
        
        $attached = $user->annoncesFavorisees()->toggle($idAnnonce);
        
        return response()->json([
            'status' => 'success',
            'is_favorite' => count($attached['attached']) > 0
        ]);
    }

    public function syncFavorites(Request $request)
    {
        $request->validate(['favorites' => 'required|array', 'favorites.*' => 'integer|exists:annonce,idannonce']);
        
        $user = Auth::user();
        $favorites = $request->favorites;
        
        if (!empty($favorites)) {
            $user->annoncesFavorisees()->syncWithoutDetaching($favorites);
        }
        
        return response()->json(['status' => 'success']);
    }

    public function searches()
    {
        $searches = Auth::user()->recherches()
            ->with(['ville', 'departement', 'region', 'dateDebut', 'dateFin', 'typesHebergement', 'commodites'])
            ->orderBy('idrecherche', 'desc')
            ->get();

        return view('user-account.searches', [
            'searches' => $searches
        ]);
    }

    public function destroySearch($id)
    {
        $search = Auth::user()->recherches()->findOrFail($id);
        $search->delete();

        return back()->with('success', 'Recherche supprimée avec succès.');
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
        $user->load(['particulier.dateNaissance', 'professionnels', 'adresse.ville']);

        return view('user-account.settings', [
            'user' => $user,
            'secteurs' => self::SECTEURS 
        ]);
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'telephone' => ['required', 'regex:/^0[1-9][0-9]{8}$/'],
            'email' => [
                'required', 'string', 'email', 'max:320',
                Rule::unique('utilisateur', 'email')->ignore($user->idutilisateur, 'idutilisateur'),
            ],
            'numerorue'  => ['nullable', 'integer', 'min:1', 'max:99999'],
            'nomrue'     => ['required', 'string', 'max:39'],
            'codepostal' => ['required', 'string', 'size:5', 'regex:/^[0-9]{5}$/'],
            'nomville'   => ['required', 'string', 'max:40'],
        ];

        if ($user->particulier) {
            $rules['civilite']       = ['required', 'in:Monsieur,Madame,Non spécifié'];
            $rules['nom']            = ['required', 'string', 'max:50'];
            $rules['prenom']         = ['required', 'string', 'max:50'];
            $rules['date_naissance'] = ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString()];
        } elseif ($user->professionnels) {
            $rules['nomsociete']      = ['required', 'string', 'max:30'];
            $rules['numsiret']        = [
                'required', 'numeric', 'digits:14',
                Rule::unique('professionnel', 'numsiret')->ignore($user->idutilisateur, 'idutilisateur')
            ];
            $rules['secteuractivite'] = ['required', 'string', Rule::in(self::SECTEURS)];
        }

        $validated = $request->validate($rules);

        $user->telephoneutilisateur = $validated['telephone'];
        $user->email                = $validated['email'];
        
        $user->save();

        if ($user->particulier) {
            $dateModel = DateModel::firstOrCreate(['date' => $validated['date_naissance']]);

            $particulier = $user->particulier;
            $particulier->civilite = $validated['civilite'];
            $particulier->iddate   = $dateModel->iddate;
            if (isset($validated['nom'])) {
                $particulier->nomutilisateur = $validated['nom'];
            }

            if (isset($validated['prenom'])) {
                $particulier->prenomutilisateur = $validated['prenom'];
            }
            $particulier->save();
        } elseif ($user->professionnels) {
            $pro = $user->professionnels;
            $pro->nomsociete      = $validated['nomsociete'];
            $pro->numsiret        = $validated['numsiret'];
            $pro->secteuractivite = $validated['secteuractivite'];
            $pro->save();
        }

        $nomVilleSaisi = strtoupper($validated['nomville']);
        $codePostal    = $validated['codepostal'];

        $ville = Ville::where('nomville', $nomVilleSaisi)
            ->where('codepostal', $codePostal)
            ->first();

        if (!$ville) {
            $communeData = null;
            try {
                $http = Http::timeout(5);
                if (app()->environment('local')) $http = $http->withoutVerifying();
                
                $response = $http->get('https://geo.api.gouv.fr/communes', [
                    'codePostal' => $codePostal,
                    'fields'     => 'nom,code,codesPostaux,codeDepartement,codeRegion',
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
                    if (!$communeData && !empty($communes)) $communeData = $communes[0];
                }
            } catch (\Throwable $e) { 
                $communeData = null; 
            }

            $codeRegionApi      = $communeData['codeRegion'] ?? 'INCONNU';
            $codeDepartementApi = $communeData['codeDepartement'] ?? substr($codePostal, 0, 2);
            $nomCommuneApi      = $communeData ? strtoupper($communeData['nom']) : $nomVilleSaisi;

            if (!$communeData && in_array(substr($codePostal, 0, 2), ['97', '98'])) {
                $codeDepartementApi = substr($codePostal, 0, 3);
            }

            $region = Region::firstOrCreate(['nomregion' => 'REGION ' . $codeRegionApi]);
            
            $departement = Departement::firstOrCreate(
                ['numerodepartement' => $codeDepartementApi],
                ['idregion' => $region->idregion, 'nomdepartement' => 'DEPARTEMENT ' . $codeDepartementApi]
            );

            $ville = Ville::firstOrCreate(
                [
                    'codepostal' => $codePostal,
                    'nomville'   => $nomCommuneApi
                ],
                [
                    'iddepartement' => $departement->iddepartement,
                    'taxedesejour'  => random_int(50, 300) / 100
                ]
            );
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