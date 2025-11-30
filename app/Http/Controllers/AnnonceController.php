<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Cache;

class AnnonceController extends Controller
{
    public function index() {
        return view("index");
    }

    public function view($id)
    {
        $annonce = Annonce::with([
            'photos',
<<<<<<< HEAD
            'commodites.typeEquipement', 
            'adresse.ville',
            'datePublication',
            'heurearrivee',
            'heuredepart',
            'utilisateur',
            'chambres'
=======
            'chambres',
            'commodites',
            'typehebergement',
            'avis',
            'adresse.ville',
            'utilisateur',
            'heurearrivee',
            'heuredepart',
            'annonces.photos',
            'annonces.adresse.ville',
>>>>>>> ebfc1ff1a0bd9574fc404cdd59d6640c63a96e80
        ])
        ->withAvg('avis', 'nombreetoiles')
        ->withCount('avis')
        ->findOrFail($id);

        return view("annonce-view", ['annonce' => $annonce]);
    }
}