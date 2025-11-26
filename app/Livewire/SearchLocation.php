<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Ville;
use App\Models\Departement;
use App\Models\Region;


class SearchLocation extends Component
{
    public string $query = '';
    public array $results = [];
    protected $queryString = ['search'];

    public function render()
    {
        if (strlen($this->query) > 0) {
            $this->results = Region::select('idregion', 'nomregion')
            ->whereRaw(
                "LOWER(nomregion) LIKE ?",['%' . 
                strtolower($this->query) . '%']
            )->select('nomregion')
            ->get()->toArray();

            $this->results = array_merge($this->results, Departement::select('iddepartement', 'nomdepartement')
            ->whereRaw(
                "LOWER(nomdepartement) LIKE ?",['%' . 
                strtolower($this->query) . '%']
            )->select('nomdepartement', 'numerodepartement')
            ->get()->toArray());

            $this->results = array_merge($this->results, Ville::select('idville', 'nomville')
            ->whereRaw(
                "LOWER(nomville) LIKE ?",['%' . 
                strtolower($this->query) . '%']
            )->select('nomville', 'codepostal')
            ->get()->toArray());
            $this->results = array_slice($this->results, 0, 10);
        }
        return view('livewire.search-location');
    }
}
