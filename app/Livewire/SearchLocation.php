<?php

namespace App\Livewire;

use Livewire\Component;

class SearchLocation extends Component
{
    public string $search = '';

    public function setLocation($nom)
    {
        $this->search = $nom;
        $nomNettoye = explode(',', $nom)[0]; 

        $this->dispatch('locationSelected', $nomNettoye);
    }

    public function updatedSearch()
    {
        $this->dispatch('locationSelected', $this->search);
    }

    public function render()
    {
        return view('livewire.search-location');
    }
}