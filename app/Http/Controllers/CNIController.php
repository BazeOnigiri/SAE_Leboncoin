<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CNIController extends Controller
{
    public function index() {
        return view("cni.index");
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'recto' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
            'verso' => 'required|file|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $rectoPath = $request->file('recto')->store('cni', 'private');
        $versoPath = $request->file('verso')->store('cni', 'private');

        return redirect()->back()->with([
            'success' => 'CNI traité avec succès.',
        ]);
    }
}
