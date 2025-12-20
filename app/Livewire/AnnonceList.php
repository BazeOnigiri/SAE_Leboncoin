<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use App\Models\Date;
use App\Models\Recherche;
use App\Models\Ville;
use App\Models\Departement;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Cache;

class AnnonceList extends Component
{
    public $markers = [];

    #[Url]
    public $location = ''; 

    #[Url]
    public $filterTypes = [];

    #[Url]
    public $dateArrivee = '';

    #[Url]
    public $dateDepart = '';

    #[Url]
    public $nbVoyageurs = 1;

    #[Url]
    public $nbChambres = 0;

    #[Url]
    public $minPrice = null;

    #[Url]
    public $maxPrice = null;

    #[Url]
    public $selectedCommodites = [];

    public function saveSearch()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.check');
        }

        $idDateDebut = null;
        if ($this->dateArrivee) {
            $idDateDebut = Date::firstOrCreate(['date' => $this->dateArrivee])->iddate;
        }

        $idDateFin = null;
        if ($this->dateDepart) {
            $idDateFin = Date::firstOrCreate(['date' => $this->dateDepart])->iddate;
        }

        $idVille = null;
        $idDepartement = null;
        $idRegion = null;

        if ($this->location) {
            $ville = Ville::where('nomville', 'ilike', $this->location)->first();
            if ($ville) {
                $idVille = $ville->idville;
                $idDepartement = $ville->iddepartement; 
            } else {
                $dept = Departement::where('nomdepartement', 'ilike', $this->location)->first();
                if ($dept) {
                    $idDepartement = $dept->iddepartement;
                } else {
                    $region = Region::where('nomregion', 'ilike', $this->location)->first();
                    if ($region) {
                        $idRegion = $region->idregion;
                    }
                }
            }
        }

        $recherche = Recherche::create([
            'idutilisateur' => Auth::id(),
            'idville' => $idVille,
            'iddepartement' => $idDepartement,
            'idregion' => $idRegion,
            'iddatedebutrecherche' => $idDateDebut,
            'iddatefinrecherche' => $idDateFin,
            'capaciteminimumvoyageur' => $this->nbVoyageurs > 1 ? $this->nbVoyageurs : null,
            'nombreminimumchambre' => $this->nbChambres > 0 ? $this->nbChambres : null,
            'prixminimum' => ($this->minPrice === '' || $this->minPrice === null) ? null : $this->minPrice,
            'prixmaximum' => ($this->maxPrice === '' || $this->maxPrice === null) ? null : $this->maxPrice,
            'paiementenligne' => false, 
        ]);

        if (!empty($this->filterTypes)) {
            $recherche->typesHebergement()->attach($this->filterTypes);
        }

        if (!empty($this->selectedCommodites)) {
            $recherche->commodites()->attach($this->selectedCommodites);
        }

        $this->dispatch('search-saved'); 
        back()->with('success', 'Recherche sauvegardée avec succès !');
    }

    #[On('locationSelected')] 
    public function updateLocation($nom) { $this->location = $nom; }

    #[On('filtersUpdated')]
    public function updateFilters(array $types = [], string $dateArrivee = '', string $dateDepart = '', int $nbVoyageurs = 1, int $nbChambres = 0, $minPrice = null, $maxPrice = null, array $commodites = [])  
    {
        $this->filterTypes = $types;
        $this->dateArrivee = $dateArrivee; 
        $this->dateDepart = $dateDepart;
        $this->nbVoyageurs = $nbVoyageurs;
        $this->nbChambres = $nbChambres;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->selectedCommodites = $commodites;
    }

    public function render()
    {
        $query = Annonce::select(
            'idannonce', 'idadresse', 'idtypehebergement', 'titreannonce', 
            'prixnuitee', 'nombreetoilesleboncoin', 'capacite'
        )->with([
            'photos', 'typehebergement', 'adresse.ville.departement.region', 
        ])->where('estverifie', true);

        if (!empty($this->location)) {
            $loc = $this->location;
            $query->whereHas('adresse.ville', function ($q) use ($loc) {
                $q->where('nomville', 'ilike', "%{$loc}%")
                ->orWhere('codepostal', 'like', "{$loc}%") 
                ->orWhereHas('departement', function ($q2) use ($loc) {
                    $q2->where('nomdepartement', 'ilike', "%{$loc}%")
                        ->orWhereHas('region', function ($q3) use ($loc) {
                            $q3->where('nomregion', 'ilike', "%{$loc}%");
                        });
                });
            });
        }
        if (!empty($this->filterTypes)) {
            $query->whereIn('idtypehebergement', $this->filterTypes);
        }
        if (!empty($this->dateArrivee) || !empty($this->dateDepart)) {
            $debut = $this->dateArrivee ?: $this->dateDepart;
            $fin   = $this->dateDepart  ?: $this->dateArrivee;
            $query->whereRaw("idannonce IN (SELECT id_annonce FROM get_annonces_disponibles(?, ?))", [
                $debut,
                $fin
            ]);
        }
        
        if ($this->nbVoyageurs > 1) {
            $query->whereRaw("idannonce IN (SELECT id_annonce FROM get_annonces_par_capacite(?))", [(int)$this->nbVoyageurs]);
        }
        if ($this->nbChambres > 0) {
            $query->whereRaw("idannonce IN (SELECT id_annonce FROM get_annonces_par_nb_chambres(?))", [(int)$this->nbChambres]);
        }
        if (!empty($this->selectedCommodites)) {
            $commoditesStr = '{' . implode(',', array_map('intval', $this->selectedCommodites)) . '}';
            $query->whereRaw("idannonce IN (SELECT id_annonce FROM get_annonces_par_commodites(?::int[]))", [$commoditesStr]);
        }

        if ($this->minPrice !== null && $this->minPrice !== '') {
            $query->where('prixnuitee', '>=', (float)$this->minPrice);
        }
        if ($this->maxPrice !== null && $this->maxPrice !== '') {
            $query->where('prixnuitee', '<=', (float)$this->maxPrice);
        }

        $annonces = $query->get();
        $cacheKeys = $annonces->map(fn($a) => 'gps_v2_adresse_' . $a->idadresse)->toArray();
        $allCoords = Cache::many($cacheKeys);

        $this->markers = $annonces->map(function ($annonce) use ($allCoords) {
            if (!$annonce->adresse) return null; 

            $key = 'gps_v2_adresse_' . $annonce->idadresse;
            
            $coords = $allCoords[$key] ?? null;

            if (!$coords) return null; 

            return [
                'id' => $annonce->idannonce,
                'lat' => $coords['lat'],
                'lng' => $coords['lng'],
                'title' => $annonce->titreannonce,
                'price' => $annonce->prixnuitee,
                'img' => $annonce->photos->first()->lienphoto ?? null 
            ];
        })->filter()->values()->toArray(); 

        $this->dispatch('update-map');

        return view('livewire.annonce-list', [
            'annonces' => $annonces
        ]);
    }
}