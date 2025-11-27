<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ville;
use App\Models\Departement;
use App\Models\Region;
use Illuminate\Support\Facades\Cache;

class SearchLocation extends Component
{
    public string $query = '';
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

    public function render()
    {
        $this->results = [];

        if (strlen($this->query) > 0) {
            $q = strtolower($this->query);

            $results = [];

            foreach ($this->regions as $region) {
                if (str_contains(strtolower($region['nomregion']), $q)) {
                    $results[] = $region;
                }
            }

            foreach ($this->departements as $dep) {
                if (str_contains(strtolower($dep['nomdepartement']), $q)) {
                    $results[] = $dep;
                }
            }

            foreach ($this->villes as $ville) {
                if (str_contains(strtolower($ville['nomville']), $q)) {
                    $results[] = $ville;
                }
            }

            $this->results = array_slice($results, 0, 10);
        }

        return view('livewire.search-location');
    }
}
