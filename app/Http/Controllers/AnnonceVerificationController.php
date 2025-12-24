<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnonceVerificationController extends Controller
{
    public function show($annonce)
    {
        $annonce = Annonce::findOrFail($annonce);
        
        abort_if($annonce->idutilisateur !== Auth::id(), 403);
        
        if ($annonce->sms_verification_code === null && $annonce->estverifie) {
            return redirect()->route('user.annonces')->with('info', 'Cette annonce est déjà vérifiée.');
        }

        return view('annonce.verify-sms', compact('annonce'));
    }

    public function resend($annonce, SmsService $smsService)
    {
        $annonce = Annonce::findOrFail($annonce);
        abort_if($annonce->idutilisateur !== Auth::id(), 403);

        $user = Auth::user();
        
        if (empty($user->telephoneutilisateur)) {
            return back()->with('error', 'Vous devez renseigner un numéro de téléphone dans votre profil.');
        }

        $code = $smsService->generateCode();
        
        $annonce->update([
            'sms_verification_code' => $code,
            'sms_verification_expires_at' => now()->addMinutes(10),
        ]);

        $message = "Leboncoin : Votre code de vérification pour l'annonce \"{$annonce->titreannonce}\" est : {$code}. Valide 10 minutes.";
        $smsService->send($user->telephoneutilisateur, $message);

        return back()->with('success', 'Un nouveau code SMS a été envoyé.');
    }

    public function verify(Request $request, $annonce)
    {
        $annonce = Annonce::findOrFail($annonce);
        abort_if($annonce->idutilisateur !== Auth::id(), 403);

        $validated = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        if ($annonce->sms_verification_expires_at && now()->gt($annonce->sms_verification_expires_at)) {
            return back()->with('error', 'Le code a expiré. Veuillez en demander un nouveau.');
        }

        if ($annonce->sms_verification_code !== $validated['code']) {
            return back()->with('error', 'Code incorrect. Veuillez réessayer.');
        }

        $annonce->update([
            'sms_verification_code' => null,
            'sms_verification_expires_at' => null,
        ]);

        return redirect()->route('user.annonces')->with('success', 'Votre annonce a été vérifiée par SMS ! Elle sera visible après validation par notre équipe.');
    }
}

