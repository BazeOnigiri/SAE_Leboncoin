<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index(){
        $annonces = Annonce::select(
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
        ])->paginate(10);

        return view("index", compact('annonces'));
    }
}
