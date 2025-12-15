<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConnexionController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.check-email');
    }


    public function checkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ], [
            'email.required' => "L'adresse e-mail est obligatoire pour continuer.",
            'email.email'    => "Le format de l'adresse e-mail n'est pas valide (ex: nom@domaine.com).",
            'email.max'      => "L'adresse e-mail est trop longue (max 255 caractères).",
        ]);
        
        $exists = User::where('email', $validated['email'])->exists();

        if ($exists) {
            return redirect()->route('login', ['email' => $validated['email']]);
        } else {
            return redirect()->route('register', ['email' => $validated['email']]);
        }
    }
}