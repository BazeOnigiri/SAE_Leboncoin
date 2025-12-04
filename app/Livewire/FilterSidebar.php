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

    public function mount()
    {
        $this->typesHebergement = Cache::rememberForever('types_hebergement', function () {
            return TypeHebergement::select('idtypehebergement', 'nomtypehebergement')
                ->orderBy('nomtypehebergement')
                ->get()
                ->toArray();
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
            dateDepart: $this->dateDepart
        );
        
        $this->dispatch('close-filter-panel'); 
    }

    public function render()
    {
        return view('livewire.filter-sidebar');
    }
}