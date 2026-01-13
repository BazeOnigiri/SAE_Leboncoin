<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Commodite;
use Livewire\WithPagination;

class ServiceAnnonceEditor extends Component
{
    use WithPagination;

    public $selectedTypes = [];
    public $selectedEquipements = [];

    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Annonce::query()->with(['typehebergement', 'photos', 'utilisateur']);

        if (!empty($this->selectedTypes)) {
            $query->whereIn('idtypehebergement', $this->selectedTypes);
        }

        if (!empty($this->selectedEquipements)) {
            foreach ($this->selectedEquipements as $equipementId) {
                $query->whereHas('commodites', function ($q) use ($equipementId) {
                    $q->where('commodite.idcommodite', $equipementId);
                });
            }
        }

        $annoncesPaginees = $query->orderBy('idannonce', 'asc')
                                  ->paginate(10);

        return view('livewire.service-annonce-editor', [
            'annonces' => $annoncesPaginees,
            'typesHebergement' => TypeHebergement::orderBy('nomtypehebergement', 'asc')->get(),
            'equipements' => Commodite::whereHas('categorie', function($query) {
                $query->where('nomcategorie', 'Équipements');
            })->orderBy('nomcommodite', 'asc')->get(),
        ]);
    }
}