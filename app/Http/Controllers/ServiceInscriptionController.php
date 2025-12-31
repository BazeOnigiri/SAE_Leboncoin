<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ServiceInscriptionController extends Controller
{
    public function index()
    {
        $users = User::doesntHave('roles')
            ->where(function ($query) {
                $query->where('phone_verified', false)
                      ->orWhereNull('email_verified_at');
            })
            ->orderBy('idutilisateur', 'desc')
            ->paginate(25);
        return view('services.inscription', compact('users'));
    }
}
