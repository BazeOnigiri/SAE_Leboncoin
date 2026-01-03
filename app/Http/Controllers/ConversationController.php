<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Message;
use App\Models\Date;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConversationController extends Controller
{
    public function show(Reservation $reservation)
    {
        $user = Auth::user();
        
        $isClient = $reservation->idutilisateur === $user->idutilisateur;
        $isOwner = $reservation->annonce->idutilisateur === $user->idutilisateur;
        
        if (!$isClient && !$isOwner) {
            abort(403, 'Vous n\'êtes pas autorisé à accéder à cette conversation.');
        }

        Message::where('idreservation', $reservation->idreservation)
            ->where('idutilisateurreceveur', $user->idutilisateur)
            ->where('lu', false)
            ->update(['lu' => true]);

        $messages = $reservation->messages()
            ->with(['expediteur', 'receveur'])
            ->get();

        if ($isClient) {
            $interlocuteur = $reservation->annonce->utilisateur;
        } else {
            $interlocuteur = $reservation->utilisateur;
        }

        return view('conversation.show', compact('reservation', 'messages', 'interlocuteur', 'isClient', 'isOwner'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        $user = Auth::user();
        
        $isClient = $reservation->idutilisateur === $user->idutilisateur;
        $isOwner = $reservation->annonce->idutilisateur === $user->idutilisateur;
        
        if (!$isClient && !$isOwner) {
            abort(403, 'Vous n\'êtes pas autorisé à envoyer un message dans cette conversation.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if ($isClient) {
            $destinataireId = $reservation->annonce->idutilisateur;
        } else {
            $destinataireId = $reservation->idutilisateur;
        }

        $today = Carbon::now();
        $dateEntry = Date::firstOrCreate(
            ['date' => $today->format('Y-m-d')]
        );

        Message::create([
            'idutilisateurexpediteur' => $user->idutilisateur,
            'idutilisateurreceveur' => $destinataireId,
            'iddate' => $dateEntry->iddate,
            'contenumessage' => $request->message,
            'idreservation' => $reservation->idreservation,
            'lu' => false,
            'created_at' => $today,
        ]);

        return redirect()->route('conversation.show', $reservation)
            ->with('success', 'Message envoyé !');
    }

    public function countUnread(Reservation $reservation)
    {
        $user = Auth::user();
        
        $count = Message::where('idreservation', $reservation->idreservation)
            ->where('idutilisateurreceveur', $user->idutilisateur)
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function countAllUnread()
    {
        $user = Auth::user();
        
        $count = Message::where('idutilisateurreceveur', $user->idutilisateur)
            ->where('lu', false)
            ->whereNotNull('idreservation')
            ->count();

        return response()->json(['count' => $count]);
    }
}
