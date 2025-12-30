<?php

namespace App\Http\Controllers;

use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneVerificationController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user->phone_verified) {
            return redirect()->route('dashboard')->with('info', 'Votre téléphone est déjà vérifié.');
        }

        return view('auth.verify-phone');
    }

    public function resend(SmsService $smsService)
    {
        $user = Auth::user();

        if (empty($user->telephoneutilisateur)) {
            return back()->with('error', 'Vous devez renseigner un numéro de téléphone dans votre profil.');
        }

        $code = $smsService->generateCode();

        $user->update([
            'phone_verification_code' => $code,
            'phone_verification_expires_at' => now()->addMinutes(10),
        ]);

        $message = "Leboncoin : Votre code de vérification est : {$code}. Valide 10 minutes.";
        $smsService->send($user->telephoneutilisateur, $message);

        return back()->with('success', 'Un nouveau code SMS a été envoyé.');
    }

    public function verify(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        if ($user->phone_verification_expires_at && now()->gt($user->phone_verification_expires_at)) {
            return back()->with('error', 'Le code a expiré. Veuillez en demander un nouveau.');
        }

        if ($user->phone_verification_code !== $validated['code']) {
            return back()->with('error', 'Code incorrect. Veuillez réessayer.');
        }

        $user->update([
            'phone_verified' => true,
            'phone_verification_code' => null,
            'phone_verification_expires_at' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Votre téléphone a été vérifié par SMS !');
    }
}