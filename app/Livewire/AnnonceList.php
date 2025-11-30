<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use Livewire\Attributes\On;

class AnnonceList extends Component
{
    public $location = ''; 
    public $filterTypes = [];
    
    public $dateArrivee = '';
    public $dateDepart = '';

    #[On('locationSelected')] 
    public function updateLocation($nom)
    {
        $this->location = $nom;
    }

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

            
            $query->whereHas('dates', function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end])
                ->where('estdisponible', true);
            });
        }

        $annonces = $query->get();

        return view('livewire.annonce-list', [
            'annonces' => $annonces
        ]);
    }
}