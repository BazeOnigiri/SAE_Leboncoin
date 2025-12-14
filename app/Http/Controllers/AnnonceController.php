<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Adresse;
use App\Models\Photo;
use App\Models\Date as DateModel;
use App\Models\Heure;
use App\Models\Ville;
use App\Models\Categorie;
use Illuminate\Http\RedirectResponse;

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
        ])
        ->withAvg('avis', 'nombreetoiles')
        ->withCount('avis')
        ->findOrFail($id);

        $commoditesGroupees = $annonce->commodites->groupBy(function ($commodite) {
            return $commodite->categorie->nomcategorie ?? 'Autres';
        });
        return view("annonce-view", compact('annonce', 'commoditesGroupees'));
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
        if (!auth()->user()->isCNIValidate())
        {
            return redirect()->route('cni.index')->with('info', 'Vous devez valider votre identité avant de pouvoir publier une annonce.');
        }
        $typeHebergements = TypeHebergement::all();
        $categories = Categorie::with('commodites')->get();
        return view("annonce-create", compact('typeHebergements', 'categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isCNIValidate())
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
            'prixnuitee' => ['required', 'numeric', 'min:0', 'max:10000'],
            'minimumnuitee' => ['required', 'integer', 'min:1', 'max:365'],
            'pourcentageacompte' => ['required', 'integer', 'min:0', 'max:100'],
            'commodites' => ['nullable', 'array'],
            'commodites.*' => ['integer', 'exists:commodite,idcommodite'],
            'numerorue'  => ['nullable', 'integer', 'min:1', 'max:99999'],
            'nomrue'     => ['required', 'string', 'max:39'],
            'codepostal' => ['required', 'string', 'size:5', 'regex:/^[0-9]{5}$/'],
            'ville'      => ['required', 'string', 'max:40'],
        ]);

        try {
            $annonce = DB::transaction(function () use ($validated, $request) {

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
                    'idadresse' => '1',
                    'iddate' => $date->iddate,
                    'idutilisateur' => Auth::id(),
                    'capacite' => $validated['capacite'],
                    'idheuredepart' => $heureDepart->idheure,
                    'idtypehebergement' => $validated['typebien'],
                    'idheurearrivee' => $heureArrivee->idheure,
                    'titreannonce' => $validated['titre'],
                    'descriptionannonce' => $validated['description'],
                    'prixnuitee' => $validated['prixnuitee'],
                    'minimumnuitee' => $validated['minimumnuitee'],
                    'pourcentageacompte' => $validated['pourcentageacompte'],
                    'possibilitefumeur' => (bool) $validated['possibilitefumeur'],
                    'nbchambres' => $validated['nbchambres'],
                ]);

                // Save photos if present
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $file) {
                        if (!$file->isValid()) continue;
                        $path = $file->store('annonces', 'public');
                        $url = Storage::url($path);
                        Photo::create([
                            'idannonce' => $annonce->idannonce,
                            'idincident' => null,
                            'lienphoto' => $url,
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
            
            return back()->with('success', 'Annonce publiée avec succès.');
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'annonce.' . $e->getMessage()]);
        }
    }
}