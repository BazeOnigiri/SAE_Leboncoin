<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TypeHebergement;
use Illuminate\Support\Facades\Cache;

class FilterSidebar extends Component
{
    public array $typesHebergement = [];
    public array $selectedTypes = []; // Stocke les IDs des types cochés
    public bool $showTypes = false; // Pour ouvrir/fermer la section

    public function mount()
    {
        // On charge les types depuis la BDD (avec cache pour la perf)
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

    // Cette fonction est appelée quand on clique sur "Rechercher"
    public function applyFilters()
    {
        // On envoie les filtres au composant AnnonceList
        $this->dispatch('filtersUpdated', types: $this->selectedTypes);
        
        // Optionnel : fermer la sidebar via un événement browser si vous utilisez AlpineJS pour le panel
        $this->dispatch('close-filter-panel'); 
    }

    public function render()
    {
        return view('livewire.filter-sidebar');
    }
}