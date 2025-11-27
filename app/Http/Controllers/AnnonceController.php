<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Cache;

class AnnonceController extends Controller
{
    public function index() {

        $annonces = Cache::rememberForever('annonces_index', function () {
            return Annonce::select(
                'idannonce',
                'idadresse',
                'idtypehebergement',
                'titreannonce',
                'prixnuitee',
                'nombreetoilesleboncoin'
            )->with([
                'photos',
                'chambres',
                'typehebergement',
                'adresse.ville',
            ])->get();
        });

        return view("index", compact('annonces'));
    }
}
