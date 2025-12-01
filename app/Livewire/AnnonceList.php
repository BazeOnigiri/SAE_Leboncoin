<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use App\Models\Date;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache; // <--- IMPERATIF
use Illuminate\Support\Facades\Http;  // <--- IMPERATIF

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
            'prixnuitee', 'nombreetoilesleboncoin'
        )->with([
            'photos', 'chambres', 'typehebergement', 'adresse.ville.departement.region', 
        ]);

        // --- FILTRES (Inchangés) ---
        if (!empty($this->location)) {
            $loc = $this->location;
            $query->whereHas('adresse.ville', function ($q) use ($loc) {
                $q->where('nomville', 'like', "%{$loc}%")
                ->orWhere('codepostal', 'like', "{$loc}%")
                ->orWhereHas('departement', function ($q2) use ($loc) {
                    $q2->where('nomdepartement', 'like', "%{$loc}%")
                        ->orWhereHas('region', function ($q3) use ($loc) {
                            $q3->where('nomregion', 'like', "%{$loc}%");
                        });
                });
            });
        }
        if (!empty($this->filterTypes)) {
            $query->whereIn('idtypehebergement', $this->filterTypes);
        }
        if (!empty($this->dateArrivee) && !empty($this->dateDepart)) {
            $start = $this->dateArrivee;
            $end = $this->dateDepart;
            $dateIds = Date::whereBetween('date', [$start, $end])->pluck('iddate');
            $query->whereHas('dates', function ($q) use ($dateIds) {
                $q->whereIn('relier.iddate', $dateIds)->where('relier.estdisponible', true);
            });
        }

        $annonces = $query->get();

        $this->markers = $annonces->map(function ($annonce) {
        if (!$annonce->adresse) return null;

        $coords = Cache::get('gps_v2_adresse_' . $annonce->idadresse);
        
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