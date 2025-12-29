<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ServiceInscriptionController extends Controller
{
    public function index()
    {
        $users = User::doesntHave('roles')->whereNull('email_verified_at')->paginate(20);
        return view('services.inscription', compact('users'));
    }
}
