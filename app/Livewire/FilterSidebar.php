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
    public $minPrice = null;
    public $maxPrice = null;
    
    // Categories
    public array $equipements = [];
    public array $exterieurs = [];
    public array $services = [];

    // Selections
    public array $selectedEquipements = [];
    public array $selectedExterieur = [];
    public array $selectedServices = [];

    public bool $showEquipements = false;
    public bool $showExterieur = false;
    public bool $showServices = false;

    public function mount()
    {
        $this->typesHebergement = Cache::rememberForever('types_hebergement', function () {
            return TypeHebergement::select('idtypehebergement', 'nomtypehebergement')
                ->orderBy('nomtypehebergement')
                ->get()
                ->toArray();
        });

        $categories = Cache::rememberForever('categories_commodites_full', function () {
            return \App\Models\Categorie::with('commodites')->get();
        });

        foreach ($categories as $cat) {
            $commodites = $cat->commodites->map(function ($com) {
                return [
                    'idequipement' => $com->idcommodite, 
                    'idexterieur' => $com->idcommodite,
                    'idservice' => $com->idcommodite,
                    'nomequipement' => $com->nomcommodite,
                    'nomexterieur' => $com->nomcommodite,
                    'nomservice' => $com->nomcommodite,
                    'id' => $com->idcommodite,
                    'nom' => $com->nomcommodite
                ];
            })->toArray();

            // 1=Equipements, 2=Extérieur, 3=Services
            if ($cat->nomcategorie === 'Équipements' || $cat->idcategorie == 1) {
                $this->equipements = $commodites;
            } elseif ($cat->nomcategorie === 'Extérieur' || $cat->idcategorie == 2) {
                $this->exterieurs = $commodites;
            } elseif ($cat->nomcategorie === 'Services & accessibilité' || $cat->idcategorie == 3) {
                $this->services = $commodites;
            }
        }
    }

    public function toggleTypes()
    {
        $this->showTypes = !$this->showTypes;
    }

    public function resetFilters()
    {
        $this->selectedTypes = [];
        $this->selectedEquipements = [];
        $this->selectedExterieur = [];
        $this->selectedServices = [];
        $this->minPrice = null;
        $this->maxPrice = null;
        $this->nbChambres = 0;
        $this->nbVoyageurs = 1;
        $this->dateArrivee = '';
        $this->dateDepart = '';
    }

    public function applyFilters()
    {
        if ($this->dateArrivee && $this->dateDepart && $this->dateArrivee > $this->dateDepart) {
            $temp = $this->dateArrivee;
            $this->dateArrivee = $this->dateDepart;
            $this->dateDepart = $temp;
        }

        if ($this->minPrice !== null && $this->maxPrice !== null && 
            $this->minPrice !== '' && $this->maxPrice !== '' && 
            (float)$this->minPrice > (float)$this->maxPrice) {
            $temp = $this->minPrice;
            $this->minPrice = $this->maxPrice;
            $this->maxPrice = $temp;
        }

        $allCommodites = array_merge(
            $this->selectedEquipements,
            $this->selectedExterieur,
            $this->selectedServices
        );

        $this->dispatch('filtersUpdated', 
            types: $this->selectedTypes,
            dateArrivee: $this->dateArrivee,
            dateDepart: $this->dateDepart,
            nbVoyageurs: $this->nbVoyageurs,
            nbChambres: $this->nbChambres,
            minPrice: $this->minPrice,
            maxPrice: $this->maxPrice,
            commodites: $allCommodites
        );
        
        $this->dispatch('close-filter-panel'); 
    }

    public function render()
    {
        return view('livewire.filter-sidebar');
    }
}