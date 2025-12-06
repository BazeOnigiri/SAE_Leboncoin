<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use App\Models\Date;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache; 

class AnnonceList extends Component
{
    public $markers = [];
    public $location = ''; 
    public $filterTypes = [];
    public $dateArrivee = '';
    public $dateDepart = '';

    #[On('locationSelected')] 
    public function updateLocation($nom) { $this->location = $nom; }

    #[On('filtersUpdated')]
    public function updateFilters($types, $dateArrivee, $dateDepart) 
    {
        $this->filterTypes = $types;
        $this->dateArrivee = $dateArrivee; 
        $this->dateDepart = $dateDepart;   
    }

    public function render()
    {
        $query = Annonce::select(
            'idannonce', 'idadresse', 'idtypehebergement', 'titreannonce', 
            'prixnuitee', 'nombreetoilesleboncoin', 'capacite'
        )->with([
            'photos', 'typehebergement', 'adresse.ville.departement.region', 
        ]);

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