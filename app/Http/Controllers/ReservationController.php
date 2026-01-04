<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Annonce;
use App\Models\Message;
use App\Models\Date;
use App\Models\Transaction;
use App\Models\CarteBancaire;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function mesReservations()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $allReservations = Reservation::where('idutilisateur', Auth::id())
            ->with(['annonce.photos', 'annonce.adresse.ville', 'dateDebut', 'dateFin', 'transaction']) 
            ->orderBy('iddatedebutreservation', 'desc')
            ->get();

        $reservationsAVenir = $allReservations->filter(function ($value) {
            return $value->est_passee == false;
        });

        $reservationsPassees = $allReservations->filter(function ($value) {
            return $value->est_passee == true;
        });
        
        return view('user-account.reservations', compact('reservationsAVenir', 'reservationsPassees'));
    }
    public function create(Request $request, $id)
    {
        $annonce = Annonce::findOrFail($id);
        $arrivee = $request->query('arrivee');
        $depart = $request->query('depart');
        return view('reservation-create', compact('annonce', 'arrivee', 'depart'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required',
            'date_fin' => 'required',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'babies' => 'nullable|integer|min:0',
            'prenomutilisateur' => 'required|string|max:50',
            'nomutilisateur' => 'required|string|max:50',
            'telephoneutilisateur' => 'nullable|string|max:10',
            'message' => 'nullable|string|max:2500',
        ]);

        $annonce = Annonce::findOrFail($id);

        $dateDebut = Carbon::createFromFormat('d/m/Y', $request->date_debut);
        $dateFin = Carbon::createFromFormat('d/m/Y', $request->date_fin);

        $dateDebutEntry = Date::firstOrCreate(
            ['date' => $dateDebut->format('Y-m-d')]
        );
        
        $dateFinEntry = Date::firstOrCreate(
            ['date' => $dateFin->format('Y-m-d')]
        );

        $children = $request->children ?? 0;
        $babies = $request->babies ?? 0;

        $reservation = new Reservation();
        $reservation->idannonce = $annonce->idannonce;
        $reservation->idutilisateur = Auth::id();
        $reservation->iddatedebutreservation = $dateDebutEntry->iddate;
        $reservation->iddatefinreservation = $dateFinEntry->iddate;
        $reservation->nomclient = $request->nomutilisateur;
        $reservation->prenomclient = $request->prenomutilisateur;
        $reservation->telephoneclient = $request->telephoneutilisateur;
        $reservation->nombrevoyageur = $request->adults + $children;
        $reservation->adultes = $request->adults;
        $reservation->enfants = $children;
        $reservation->bebes = $babies;
        $reservation->save();

        if ($request->filled('message')) {
            $request->session()->put('reservation_message_' . $reservation->idreservation, $request->message);
        }

        $stripeSecret = config('services.stripe.secret');
        abort_if(!$stripeSecret, 500, 'Stripe secret is not configured (STRIPE_SECRET)');

        $nights = max(1, $dateDebut->diffInDays($dateFin));
        $pricePerNight = (float) ($annonce->prixnuitee ?? 0);
        $totalRent = $pricePerNight * $nights;
        $serviceFee = $totalRent * 0.14;
        $touristTax = 4.00 * $nights * ((int) $request->adults);

        $deposit = $serviceFee + ($totalRent * 0.35);
        $amountCents = (int) round($deposit * 100);
        abort_if($amountCents < 50, 422, 'Montant de paiement invalide.');

        $stripe = new \Stripe\StripeClient($stripeSecret);

        $session = $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer_email' => Auth::user()?->email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $amountCents,
                    'product_data' => [
                        'name' => 'Acompte réservation - ' . ($annonce->titreannonce ?? 'Annonce'),
                        'description' => 'Du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y') . ' (' . $nights . ' nuits)',
                    ],
                ],
            ]],
            'metadata' => [
                'reservation_id' => (string) $reservation->idreservation,
                'annonce_id' => (string) $annonce->idannonce,
                'user_id' => (string) Auth::id(),
            ],
            'success_url' => route('reservation.stripe.success', ['reservation' => $reservation->idreservation]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('reservation.stripe.cancel', ['reservation' => $reservation->idreservation]),
        ]);

        $reservation->stripe_checkout_session_id = $session->id;
        $reservation->stripe_payment_status = $session->payment_status ?? 'unpaid';
        $reservation->save();

        return redirect()->away($session->url);
    }

    public function stripeSuccess(Request $request, Reservation $reservation)
    {
        abort_if($reservation->idutilisateur !== Auth::id(), 403);

        if ($reservation->transaction) {
            return redirect()->route('user.mes-reservations')->with('success', 'Paiement déjà confirmé.');
        }

        $sessionId = $request->query('session_id');
        abort_if(!$sessionId, 400, 'Missing session_id');

        $stripeSecret = config('services.stripe.secret');
        abort_if(!$stripeSecret, 500, 'Stripe secret is not configured (STRIPE_SECRET)');

        $stripe = new \Stripe\StripeClient($stripeSecret);
        $session = $stripe->checkout->sessions->retrieve($sessionId, []);

        abort_if(($session->metadata->reservation_id ?? null) != (string) $reservation->idreservation, 400, 'Session mismatch');
        abort_if(($session->metadata->user_id ?? null) != (string) Auth::id(), 403, 'Session user mismatch');

        if (($session->payment_status ?? null) !== 'paid') {
            $reservation->stripe_payment_status = $session->payment_status ?? 'unpaid';
            $reservation->save();

            return redirect()->route('user.mes-reservations')->with('error', 'Paiement non confirmé.');
        }

        $reservation->stripe_payment_status = 'paid';
        $reservation->stripe_payment_intent_id = $session->payment_intent ?? null;
        $reservation->stripe_checkout_session_id = $session->id;
        $reservation->save();

        $today = Carbon::now();
        $dateEntry = Date::firstOrCreate(['date' => $today->format('Y-m-d')]);

        $carte = CarteBancaire::create([
            'idutilisateur' => Auth::id(),
            'nomtitulaire' => null,
            'prenomtitulaire' => null,
            'numerocartebancaire' => null,
            'dateexpiration' => null,
            'numerocvv' => null,
        ]);

        $transaction = new Transaction();
        $transaction->iddate = $dateEntry->iddate;
        $transaction->idreservation = $reservation->idreservation;
        $transaction->idcartebancaire = $carte->idcartebancaire;
        $transaction->montanttransaction = ((int) ($session->amount_total ?? 0)) / 100;
        $transaction->save();

        $messageKey = 'reservation_message_' . $reservation->idreservation;
        $messageText = $request->session()->pull($messageKey);
        if (!empty($messageText)) {
            Message::create([
                'idutilisateurexpediteur' => Auth::id(),
                'idutilisateurreceveur' => $reservation->annonce->idutilisateur,
                'iddate' => $dateEntry->iddate,
                'contenumessage' => $messageText,
                'idreservation' => $reservation->idreservation,
                'lu' => false,
                'created_at' => $today,
            ]);
        }

        return redirect()->route('user.mes-reservations')->with('success', 'Paiement confirmé, réservation créée.');
    }

    public function stripeCancel(Request $request, Reservation $reservation)
    {
        abort_if($reservation->idutilisateur !== Auth::id(), 403);

        if (!$reservation->transaction) {
            $request->session()->forget('reservation_message_' . $reservation->idreservation);
            $reservation->delete();
        }

        return redirect()->route('user.mes-reservations')->with('error', 'Paiement annulé.');
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->idutilisateur !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette réservation.');
        }

        $request->validate([
            'adultes' => 'required|integer|min:1',
            'enfants' => 'nullable|integer|min:0',
            'bebes' => 'nullable|integer|min:0|max:6',
            'nom_client' => 'required|string|max:50',
            'prenom_client' => 'required|string|max:50',
            'telephone_client' => 'nullable|string|max:20',
        ]);

        $adultes = $request->adultes;
        $enfants = $request->enfants ?? 0;
        $bebes = $request->bebes ?? 0;
        $totalComptes = $adultes + $enfants; 
        $totalVoyageurs = $totalComptes; 
        $capacite = $reservation->annonce->capacite ?? 20;
        
        if ($totalComptes > $capacite) {
            return back()
                ->withErrors(['voyageurs' => "Le nombre d'adultes + enfants dépasse la capacité de l'annonce ({$capacite} personnes)."])
                ->withInput();
        }

        if ($bebes > 6) {
            return back()
                ->withErrors(['bebes' => 'Un maximum de 6 bébés est autorisé.'])
                ->withInput();
        }

        $reservation->nombrevoyageur = $totalVoyageurs;
        $reservation->adultes = $adultes;
        $reservation->enfants = $enfants;
        $reservation->bebes = $bebes;
        $reservation->nomclient = $request->nom_client;
        $reservation->prenomclient = $request->prenom_client;
        $reservation->telephoneclient = $request->telephone_client;
        $reservation->save();

        return redirect()->route('user.mes-reservations')
            ->with('success', 'La réservation a été modifiée avec succès.');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->idutilisateur !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à annuler cette réservation.');
        }

        if ($reservation->transaction) {
            $reservation->transaction->delete();
        }

        $reservation->delete();

        return redirect()->route('user.mes-reservations')
            ->with('success', 'La réservation a été annulée avec succès.');
    }
}