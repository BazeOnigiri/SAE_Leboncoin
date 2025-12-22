<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ServicePetiteAnnonceController extends Controller
{
    public function index()
    {
        $users = User::where('identity_verified', false)
            ->get()
            ->filter(fn (User $user) => $user->hasCniFiles());

        return view('services.petites-annonces', compact('users'));
    }
}
