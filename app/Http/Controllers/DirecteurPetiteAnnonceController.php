<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DirecteurPetiteAnnonceController extends Controller
{
    public function etatLocations()
    {
        return view('directeur-petite-annonce.statistiques');
    }
}