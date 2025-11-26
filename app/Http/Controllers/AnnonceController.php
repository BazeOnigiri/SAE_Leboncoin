<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    public function index(){
        return view("index", ['annonces' => Annonce::limit(10)->get() ]);
    }
}
