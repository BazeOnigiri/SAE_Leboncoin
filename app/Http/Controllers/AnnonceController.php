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
        return view ("annonce-view", ['annonce'=>Annonce::withAvg('avis', 'nombreetoiles')->withCount('avis')->findOrFail($id) ]);
    }
}
