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
        $request->validate(['email' => 'required|email']);

        $exists = User::where('email', $request->email)->exists();

        if ($exists) {
            return redirect()->route('login', ['email' => $request->email]);
        } else {
            return redirect()->route('register', ['email' => $request->email]);
        }
    }
}