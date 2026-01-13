<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use App\Models\TypeHebergement;
use App\Models\Commodite;
use Illuminate\Support\Facades\DB;

class ServiceAnnonceEditForm extends Component
{
    public Annonce $annonce;
    public $idtypehebergement;
    public $selectedEquipements = [];

    public function mount(Annonce $annonce)
    {
        $this->annonce = $annonce;
        $this->idtypehebergement = $annonce->idtypehebergement;
        $this->selectedEquipements = $annonce->commodites->pluck('idcommodite')->toArray();
    }

    public function save()
    {
        DB::transaction(function () {
            $this->annonce->update([
                'idtypehebergement' => $this->idtypehebergement
            ]);

            $this->annonce->commodites()->sync($this->selectedEquipements);
        });

        session()->flash('success', 'L’annonce a été mise à jour avec succès.');
        return redirect()->route('services.annonce-editor.index');
    }

    public function render()
    {
        return view('livewire.service-annonce-edit-form', [
            'types' => TypeHebergement::orderBy('nomtypehebergement', 'asc')->get(),
            'equipements' => Commodite::whereHas('categorie', function($q) {
                $q->where('nomcategorie', 'Équipements');
            })->orderBy('nomcommodite', 'asc')->get(),
        ]);
    }
}
