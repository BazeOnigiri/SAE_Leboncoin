<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Adresse;
use App\Models\Photo;
use App\Models\Date as DateModel;
use App\Models\Heure;
use App\Models\Ville;
use App\Models\Categorie;
use App\Models\Region;
use App\Models\Departement;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnonceAcceptedMail;
use App\Mail\AnnonceDeletedMail;

class AnnonceController extends Controller
{
    public function index() {
        return view("index");
    }

    public function view($id)
    {
        $annonce = Annonce::with([
            'photos',
            'datePublication',
            'commodites.categorie',
            'typehebergement',
            'avis',
            'adresse.ville',
            'utilisateur',
            'heurearrivee',
            'heuredepart',
            'annonces.photos',
            'annonces.adresse.ville',
            'reservations.dateDebut',
            'reservations.dateFin',
        ])
        ->withAvg('avis', 'nombreetoiles')
        ->withCount('avis')
        ->findOrFail($id);

        if (!$annonce->estverifie && (!Auth::check() || Auth::user()->isSuperAdminOrNoRoles())) {
            abort(404);
        }

        $commoditesGroupees = $annonce->commodites->groupBy(function ($commodite) {
            return $commodite->categorie->nomcategorie ?? 'Autres';
        });

        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = Auth::user()->annoncesFavorisees()->where('favoriser.idannonce', $id)->exists();
        }

        // Récupérer les dates réservées (uniquement les réservations en cours et futures)
        $reservedDates = [];
        $today = \Carbon\Carbon::today();
        
        foreach ($annonce->reservations as $reservation) {
            // Vérifier que la réservation n'est pas passée (date de fin >= aujourd'hui)
            if ($reservation->dateFin && $reservation->dateFin->date) {
                $endDate = \Carbon\Carbon::parse($reservation->dateFin->date);
                // Griser les dates si la réservation n'est pas encore terminée
                if ($endDate >= $today) {
                    if ($reservation->dateDebut && $reservation->dateDebut->date) {
                        $start = \Carbon\Carbon::parse($reservation->dateDebut->date);
                        $end = \Carbon\Carbon::parse($reservation->dateFin->date);
                        while ($start <= $end) {
                            $reservedDates[] = $start->format('Y-m-d');
                            $start->addDay();
                        }
                    }
                }
            }
        }
        $reservedDates = array_unique($reservedDates);

        return view("annonce-view", compact('annonce', 'commoditesGroupees', 'isFavorite', 'reservedDates'));
    }

    public function show($id)
    {
        $annonce = Annonce::with('commodites.categorie')->findOrFail($id);
        $commoditesGroupees = $annonce->commodites->groupBy(function ($commodite) {
            return $commodite->categorie->nomcategorie;
        });
        return view('nom_de_ta_vue', compact('annonce', 'commoditesGroupees'));
    }

    public function create()
    {
        if (!auth()->user()->hasCniFiles())
        {
            return redirect()->route('cni.index')->with('info', 'Vous devez valider votre identité avant de pouvoir publier une annonce.');
        }
        
        $typeHebergements = TypeHebergement::all();
        $categories = Categorie::with('commodites')->get();
        return view("annonce-create", compact('typeHebergements', 'categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasCniFiles())
        {
            return redirect()->route('cni.index')->with('info', 'Vous devez valider votre identité avant de pouvoir publier une annonce.');
        }

        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string', 'max:4000'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'capacite' => ['required', 'integer', 'min:1', 'max:100'],
            'nbchambres' => ['required', 'integer', 'min:0', 'max:100'],
            'typebien' => ['required', 'integer', 'exists:typehebergement,idtypehebergement'],
            'heuredepart' => ['required', 'date_format:H:i'],
            'heurearrivee' => ['required', 'date_format:H:i'],
            'possibilitefumeur' => ['required', 'in:0,1'],
            'possibiliteanimaux' => ['required', 'in:0,1'],
            'prixnuitee' => ['required', 'numeric', 'min:0', 'max:10000'],
            'minimumnuitee' => ['required', 'integer', 'min:1', 'max:365'],
            'pourcentageacompte' => ['required', 'integer', 'min:0', 'max:100'],
            'commodites' => ['nullable', 'array'],
            'commodites.*' => ['integer', 'exists:commodite,idcommodite'],
            'numerorue'  => ['required', 'integer', 'min:1', 'max:99999'],
            'nomrue'     => ['required', 'string', 'max:39'],
            'codepostal' => ['required', 'string', 'size:5', 'regex:/^[0-9]{5}$/'],
            'ville'      => ['required', 'string', 'max:40'],
        ]);

        try {
            $annonce = DB::transaction(function () use ($validated, $request) {
                // Create or find Ville
                $nomVilleSaisi = strtoupper($validated['ville']);
                $codePostal    = $validated['codepostal'];

                // Recherche de la ville existante
                $ville = Ville::where('nomville', $nomVilleSaisi)
                    ->where('codepostal', $codePostal)
                    ->first();

                // Si la ville n'existe pas, on interroge l'API Gouv pour la créer
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
                    } catch (\Throwable $e) { $communeData = null; }

                    // Variables pour la création
                    $codeRegionApi      = $communeData['codeRegion'] ?? 'INCONNU';
                    $codeDepartementApi = $communeData['codeDepartement'] ?? substr($codePostal, 0, 2);
                    $nomCommuneApi      = $communeData ? strtoupper($communeData['nom']) : $nomVilleSaisi;

                    // Gestion DOM-TOM
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

                $date = DateModel::firstOrCreate(
                    ['date' => now()->toDateString()]
                );

                // Create or find departure time
                $heureDepart = Heure::firstOrCreate(
                    ['heure' => $validated['heuredepart']]
                );

                // Create or find arrival time
                $heureArrivee = Heure::firstOrCreate(
                    ['heure' => $validated['heurearrivee']]
                );

                $annonce = Annonce::create([
                    'idadresse'          => $adresse->idadresse, 
                    'iddate'             => $date->iddate,
                    'idutilisateur'      => Auth::id(),
                    'capacite'           => $validated['capacite'],
                    'idheuredepart'      => $heureDepart->idheure,
                    'idtypehebergement'  => $validated['typebien'],
                    'idheurearrivee'     => $heureArrivee->idheure,
                    'titreannonce'       => $validated['titre'],
                    'descriptionannonce' => $validated['description'],
                    'prixnuitee'         => $validated['prixnuitee'],
                    'minimumnuitee'      => $validated['minimumnuitee'],
                    'pourcentageacompte' => $validated['pourcentageacompte'],
                    'possibilitefumeur'  => (bool) $validated['possibilitefumeur'],
                    'possibiliteanimaux' => (bool) $validated['possibiliteanimaux'],
                    'nbchambres'         => $validated['nbchambres'],
                ]);

                // Save photos if present
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $file) {
                        if (!$file->isValid()) continue;
                        $path = $file->store('annonces', 'public');
                        $url = Storage::url($path);
                        Photo::create([
                            'idannonce'  => $annonce->idannonce,
                            'idincident' => null,
                            'lienphoto'  => $url,
                        ]);
                    }
                }

                // Attach commodites if provided
                if (!empty($validated['commodites'])) {
                    $annonce->commodites()->sync($validated['commodites']);
                }

                $similarAnnonces = Annonce::where('idannonce', '!=', $annonce->idannonce)
                    ->where('idtypehebergement', $annonce->idtypehebergement)
                    ->where('capacite', '>=', $annonce->capacite - 2)
                    ->where('capacite', '<=', $annonce->capacite + 2)
                    ->whereBetween('prixnuitee', [
                        $annonce->prixnuitee * 0.7,
                        $annonce->prixnuitee * 1.3
                    ])
                    ->limit(10)
                    ->pluck('idannonce');
                
                if ($similarAnnonces->isNotEmpty()) {
                    $annonce->annonces()->attach($similarAnnonces);
                }

                return $annonce;
            });

            $user = Auth::user();
            if (!empty($user->telephoneutilisateur)) {
                $smsService = app(SmsService::class);
                $code = $smsService->generateCode();
                
                $annonce->update([
                    'sms_verification_code' => $code,
                    'sms_verification_expires_at' => now()->addMinutes(10),
                ]);

                $message = "Leboncoin : Votre code de vérification pour l'annonce \"{$annonce->titreannonce}\" est : {$code}. Valide 10 minutes.";
                $smsService->send($user->telephoneutilisateur, $message);

                return redirect()->route('annonce.verify-sms', $annonce)
                    ->with('success', 'Annonce créée ! Vérifiez votre téléphone pour valider.');
            }
            
            return redirect()->route('user.annonces')->with('success', 'Annonce publiée avec succès. Elle sera visible après validation.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function servicesShowStatus()
    {
        $annonces = Annonce::where('estverifie', false)
            ->with('utilisateur', 'adresse.ville', 'typehebergement', 'datePublication')
            ->orderBy('idannonce', 'desc')
            ->get();

        return view('services.annonces-status', compact('annonces'));
    }

    public function toggleSmsVerifie($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->smsverifie = !$annonce->smsverifie;
        $annonce->save();

        $status = $annonce->smsverifie ? 'activée' : 'désactivée';
        return back()->with('success', "Vérification SMS {$status} pour l'annonce #{$annonce->idannonce}");
    }

    public function immobilierAnnonces()
    {
        $annonces = Annonce::where('estverifie', false)
            ->whereHas('utilisateur', function ($query) {
                $query->where('identity_verified', true);
            })
            ->with('utilisateur', 'adresse.ville', 'typehebergement', 'datePublication')
            ->orderBy('idannonce', 'desc')
            ->get();

        return view('services.immobilier-annonces', compact('annonces'));
    }

    public function accepterAnnonce($id)
    {
        $annonce = Annonce::with('utilisateur')->findOrFail($id);
        
        $annonce->estverifie = true;
        $annonce->save();

        if ($annonce->utilisateur && $annonce->utilisateur->email) {
            Mail::to($annonce->utilisateur->email)
                ->send(new AnnonceAcceptedMail($annonce->utilisateur, $annonce->titreannonce));
        }

        return back()->with('success', "Annonce #{$annonce->idannonce} « {$annonce->titreannonce} » validée avec succès.");
    }

    public function rejeterAnnonce(Request $request, $id)
    {
        $annonce = Annonce::with(['utilisateur', 'photos', 'commodites', 'reservations', 'avis'])->findOrFail($id);
        
        $user = $annonce->utilisateur;
        $titreAnnonce = $annonce->titreannonce;
        $motif = $request->input('motif');

        DB::transaction(function () use ($annonce) {
            foreach ($annonce->photos as $photo) {
                $path = str_replace('/storage/', '', $photo->lienphoto);
                Storage::disk('public')->delete($path);
            }
            
            $annonce->photos()->delete();
            $annonce->commodites()->detach();
            $annonce->users()->detach();
            $annonce->dates()->detach();
            $annonce->annonces()->detach();
            
            $annonce->delete();
        });

        if ($user && $user->email) {
            Mail::to($user->email)
                ->send(new AnnonceDeletedMail($user, $titreAnnonce, $motif));
        }

        return back()->with('success', "Annonce « {$titreAnnonce} » rejetée et supprimée.");
    }
}