<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Annonce;
use Livewire\Attributes\On; // Important pour l'écouteur d'événement

class AnnonceList extends Component
{
    public $location = ''; 
    #[On('locationSelected')] 
    public function updateLocation($nom)
    {
        $this->location = $nom;
    }

    public function render()
    {
        $query = Annonce::select(
            'idannonce', 'idadresse', 'idtypehebergement', 'titreannonce', 
            'prixnuitee', 'nombreetoilesleboncoin'
        )->with([
            'photos',
            'chambres',
            'typehebergement',
            'adresse.ville.departement.region', 
        ]);

        if (!empty($this->location)) {
            $loc = $this->location;
            
            $query->whereHas('adresse.ville', function ($q) use ($loc) {
                $q->where('nomville', 'like', "%{$loc}%")
                ->orWhere('codepostal', 'like', "{$loc}%")
                ->orWhereHas('departement', function ($q2) use ($loc) {
                    $q2->where('nomdepartement', 'like', "%{$loc}%")
                        ->orWhereHas('region', function ($q3) use ($loc) {
                            $q3->where('nomregion', 'like', "%{$loc}%");
                        });
                });
            });
        }

        $annonces = $query->get();

        return view('livewire.annonce-list', [
            'annonces' => $annonces
        ]);
    }
}