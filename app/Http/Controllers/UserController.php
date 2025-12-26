<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
{
    $user = User::with([
                    'adresse.ville.departement',
                    'annoncesPubliees' => function ($query) {
                        $query->where('estverifie', true)->with(['photos', 'adresse.ville']);
                    },
                ])
                ->withCount('avisRecus')
                ->withAvg('avisRecus', 'nombreetoiles')
                ->withCount(['annoncesPubliees' => function ($query) {
                    $query->where('estverifie', true);
                }])
                ->findOrFail($id);

    return view('user-profile', ['user' => $user]);
}
}
