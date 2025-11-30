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
            'commodites.typeEquipement', 
            'adresse.ville',
            'datePublication',
            'heurearrivee',
            'heuredepart',
            'utilisateur',
            'chambres'
        ])
        ->withAvg('avis', 'nombreetoiles')
        ->withCount('avis')
        ->findOrFail($id);

        return view("annonce-view", ['annonce' => $annonce]);
    }
}