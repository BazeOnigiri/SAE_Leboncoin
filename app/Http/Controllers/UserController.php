<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::withCount('avis')->withCount('annoncesPubliees')->findOrFail($id);
        return view('user-profile', ['user' => $user]);
    }
}
