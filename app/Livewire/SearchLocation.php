<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ville;
use App\Models\Departement;
use App\Models\Region;
use Illuminate\Support\Facades\Cache;

class SearchLocation extends Component
{
    public string $search = '';
    public array $results = [];
    protected $queryString = ['search'];

    public array $regions = [];
    public array $departements = [];
    public array $villes = [];

    public function mount()
    {
        $this->regions = Cache::rememberForever('all_regions', function () {
            return Region::select('idregion', 'nomregion')->get()->toArray();
        });

        $this->departements = Cache::rememberForever('all_departements', function () {
            return Departement::select('iddepartement', 'nomdepartement', 'numerodepartement')->get()->toArray();
        });

        $this->villes = Cache::rememberForever('all_villes', function () {
            return Ville::select('idville', 'nomville', 'codepostal')->get()->toArray();
        });
    }

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->dispatch('locationSelected', '');
        }
    }

    public function resetResults()
    {
        $this->results = [];
    }

    public function selectOption($nom)
    {
        $this->search = $nom;
        $this->resetResults(); 
        $this->dispatch('locationSelected', $nom);
    }

    public function selectFirstMatch()
    {
        if (empty($this->search)) return;

        if (!empty($this->results)) {
            $first = $this->results[0];
            $nom = $first['nomregion'] ?? ($first['nomdepartement'] ?? ($first['nomville'] ?? ''));
            if ($nom) {
                $this->selectOption($nom);
                return;
            }
        }

        $region = Region::where('nomregion', 'like', '%' . $this->search . '%')->first();
        if ($region) {
            $this->selectOption($region->nomregion);
            return;
        }

        $departement = Departement::where('nomdepartement', 'like', '%' . $this->search . '%')->first();
        if ($departement) {
            $this->selectOption($departement->nomdepartement);
            return;
        }

        $ville = Ville::where('nomville', 'like', '%' . $this->search . '%')->first();
        if ($ville) {
            $this->selectOption($ville->nomville);
            return;
        }
    }

    public function render()
    {
        $this->results = [];

        if (strlen($this->search) > 0) {
            $q = strtolower($this->search);
            $tempResults = [];

            // Recherche dans les régions
            foreach ($this->regions as $region) {
                if (str_contains(strtolower($region['nomregion']), $q)) {
                    $tempResults[] = $region;
                }
            }

            // Recherche dans les départements
            foreach ($this->departements as $dep) {
                if (str_contains(strtolower($dep['nomdepartement']), $q)) {
                    $tempResults[] = $dep;
                }
            }

            // Recherche dans les villes
            foreach ($this->villes as $ville) {
                if (str_contains(strtolower($ville['nomville']), $q)) {
                    $tempResults[] = $ville;
                }
            }

            // On limite à 10 résultats pour ne pas surcharger l'affichage
            $this->results = array_slice($tempResults, 0, 10);
        }

        return view('livewire.search-location');
    }
}