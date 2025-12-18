<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TypeHebergement;
use App\Models\Date;
use Illuminate\Support\Facades\Cache;

class FilterSidebar extends Component
{
    public array $typesHebergement = [];
    public array $selectedTypes = []; 
    public bool $showTypes = false; 
    public $dateArrivee = '';
    public $dateDepart = '';
    public $nbVoyageurs = 1;
    public $nbChambres = 0;
    public array $categoriesCommodites = [];
    public array $selectedCommodites = [];
    public bool $showCommodites = false;

    public function mount()
    {
        $this->typesHebergement = Cache::rememberForever('types_hebergement', function () {
            return TypeHebergement::select('idtypehebergement', 'nomtypehebergement')
                ->orderBy('nomtypehebergement')
                ->get()
                ->toArray();
        });

        $this->categoriesCommodites = Cache::rememberForever('categories_commodites', function () {
            return \App\Models\Categorie::with('commodites')
                ->get()
                ->map(function ($cat) {
                    return [
                        'id' => $cat->idcategorie,
                        'nom' => $cat->nomcategorie,
                        'commodites' => $cat->commodites->map(function ($com) {
                            return [
                                'id' => $com->idcommodite,
                                'nom' => $com->nomcommodite
                            ];
                        })->toArray()
                    ];
                })->toArray();
        });
    }

    public function toggleTypes()
    {
        $this->showTypes = !$this->showTypes;
    }

    public function applyFilters()
    {
        $this->dispatch('filtersUpdated', 
            types: $this->selectedTypes,
            dateArrivee: $this->dateArrivee,
            dateDepart: $this->dateDepart,
            nbVoyageurs: $this->nbVoyageurs,
            nbChambres: $this->nbChambres,
            commodites: $this->selectedCommodites
        );
        
        $this->dispatch('close-filter-panel'); 
    }

    public function render()
    {
        return view('livewire.filter-sidebar');
    }
}