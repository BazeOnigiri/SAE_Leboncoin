<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatBotAIController extends Controller
{
    public function handle(Request $request)
    {
        $message = $request->input('message');
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['reply' => 'Erreur: Clé API manquante']);
        }

        $userContext = "";
        if (Auth::check()) {
            $user = Auth::user();
            $userContext = "L'utilisateur connecté s'appelle {$user->pseudonyme} ({$user->email}).";
        } else {
            $userContext = "L'utilisateur n'est pas connecté.";
        }

        $knowledgeBase = "
        === BASE DE CONNAISSANCES DU SITE LEBONCOIN LOCATIONS ===

        INSCRIPTION / CONNEXION :
        - Pour créer un compte : Cliquer sur 'Se connecter' → Entrer son e-mail → Compléter le formulaire (profil, adresse, mot de passe) → Valider et vérifier e-mail et téléphone
        - Pour se connecter : Page 'Connexion' → Entrer e-mail et mot de passe
        - Pour se déconnecter : Mon compte → Me déconnecter
        - Mot de passe oublié : Connexion → Mot de passe oublié → Recevoir lien par e-mail → Suivre instructions

        MON ESPACE COMPTE :
        - Vue générale avec photo de profil, nom, note moyenne (sur 5) et nombre d'avis
        - Porte-monnaie : Solde disponible en euros (en haut à droite, encadré orange)
        - Sections disponibles :
          * Annonces : Gérer mes annonces déposées
          * Réservations : Voir mes séjours (en cours et historique)
          * Profil : Modifier mon profil public (nom, photo)
          * Paramètres : Infos privées (e-mail, téléphone, adresse, notifications)
          * Connexion et sécurité : Mot de passe, 2FA, supprimer compte
        - Bouton 'Me déconnecter' en bas de la page


        NAVIGATION HEADER (en haut de page) :
        - Logo Leboncoin : retour à l'accueil
        - Bouton 'Déposer une annonce' (orange)
        - Aide : Page d'aide avec FAQ et questions fréquentes
        - Mes recherches : Voir ses recherches sauvegardées (icône cloche)
        - Favoris : Voir ses annonces favorites (icône cœur)
        - Profil utilisateur : Accès à Mon compte (photo de profil ou icône utilisateur)

        PAGE D'AIDE :
        - Accès : Cliquer sur 'Aide' dans le header
        - Questions fréquentes listées à gauche :
          * Comment créer un compte ?
          * Comment déposer une annonce ?
          * Quels sont les frais de service ?
          * Comment signaler une arnaque ?
          * Comment suivre ma réservation ?
          * J'ai oublié mon mot de passe

        DÉPOSER UNE ANNONCE :
        - Étapes : Se connecter → Cliquer sur 'Déposer une annonce' → Remplir titre, description, prix par nuit, capacité, chambres, règles (animaux, fumeur) → Ajouter photos → Valider
        - Une vérification SMS et une vérification du CNI peut etre demandée avant si le compte ne l'a pas fait avant ou modération peut être demandée avant publication
        - Gérer ses annonces : Mon compte → Annonces (consulter, modifier, suivre statut)
        - Statuts possibles : En attente de vérification SMS, En attente de validation (modération), Validée/publiée, Refusée

        PAGE D'UNE ANNONCE :
        - Photos du logement (carrousel avec flèches)
        - Prix par nuit affiché en haut à droite
        - Informations : titre, capacité (ex: 4 pers.), nombre de chambres, ville, note moyenne et nombre d'avis
        - Date de publication
        - Critères : Classement (étoiles), Capacité, Type de logement, Nombre de chambres
        - Bouton cœur pour ajouter aux favoris
        - Bouton partager
        - Profil du propriétaire avec son nom et nombre d'annonces
        - Calendrier pour sélectionner les dates (certaines annonces ont un minimum de nuits requis)

        SÉLECTION DES DATES :
        - Cliquer sur 'Arrivée' et 'Départ' pour ouvrir le calendrier
        - Le calendrier affiche 2 mois côte à côte
        - Dates disponibles en blanc, dates sélectionnées en bleu/orange
        - Minimum de nuits parfois requis (ex: 'Minimum requis: 4 nuits')
        - Résumé du séjour affiché en bas : prix total, dates, nombre de nuits
        - Cliquer sur 'Sélectionner' pour valider les dates

        RÉSERVATION :
        - Étapes complètes :
          1) Ouvrir l'annonce
          2) Sélectionner dates d'arrivée et de départ dans le calendrier
          3) Cliquer sur 'Réserver'
          4) Page 'Votre réservation' : vérifier les dates et le nombre de nuits
          5) Choisir le nombre de voyageurs : Adultes (18 ans et plus), Enfants (2 à 17 ans), Bébés (moins de 3 ans)
          6) Capacité maximale et animaux indiqués (ex: 'Capacité maximale: 4 personnes | Maximum 6 bébés | Au moins 1 adulte requis | Animaux: Non acceptés')
          7) Remplir vos informations : Prénom, Nom, Numéro de téléphone
          8) Envoyer un message au propriétaire (optionnel) : heure d'arrivée, raison du voyage...
          9) Accepter les conditions d'utilisation et CGV
          10) Cliquer sur 'Payer et valider ma réservation'

        RÉCAPITULATIF DU PAIEMENT :
        - Montant de la location (prix par nuit × nombre de nuits)
        - Frais de service (commission du site)
        - Taxe de séjour
        - Total à payer
        - À payer maintenant : acompte (environ 45% du total)
        - Reste à payer sur place : le solde restant
        - Moyens de paiement : Carte bancaire (Visa, MasterCard, American Express, etc.) et elle peut être enregistrée pour de futurs paiements

        MES VOYAGES / RÉSERVATIONS :
        - Accès : Mon compte → Réservations
        - Deux onglets : 'En cours' (réservations actuelles) et 'Historique' (réservations passées)
        - Pour chaque réservation affichée :
          * Photo et titre de l'annonce
          * Statut (ex: 'Confirmée', 'En attente de paiement')
          * Ville
          * Dates du séjour (ex: 'Du 04/01 au 08/01/2026')
          * Nombre de voyageurs
          * Montant total et reste à payer sur place
        - Actions disponibles sur une réservation :
          * Message : contacter le propriétaire
          * Modifier : changer les dates ou voyageurs
          * Détails : voir le détail complet
          * Signaler un incident : en cas de problème
          * Annuler : annuler la réservation (si disponible)

        FRAIS DE SERVICE :
        - Commission du site ajoutée au montant de la location
        - Détail visible dans le récapitulatif de paiement

        RECHERCHER UNE ANNONCE :
        - Utiliser la barre de recherche (ville, département ou région)
        - Filtres disponibles :
          * Dates : Dates de séjour (arrivée/départ)
          * Prix par nuit : Minimum et Maximum en euros
          * Chambres : Tout, 1, 2, 3, 4, 5, 6+
          * Type d'hébergement : Appartement, Maison, Villa, Loft, Gîte, Chalet, Studio, Bungalow
          * Équipements : Wifi gratuit, Télévision, Climatisation...
          * Extérieur : Piscine, Jardin, Balcon, Terrasse...
          * Services : Petit-déjeuner...
          * Locations saisonnières : Filtre spécial vacances
          * Tri : Par pertinence
        - Si aucun résultat : vérifier les dates, essayer autre destination, retirer certains filtres

        SAUVEGARDER UNE RECHERCHE :
        - Après avoir effectué une recherche, possibilité de la sauvegarder
        - Retrouver ses recherches : Cliquer sur 'Mes recherches' en haut de page (icône cloche)
        - Supprimer une recherche sauvegardée depuis la liste

        FAVORIS :
        - Ajouter aux favoris : Sur une annonce, cliquer sur l'icône cœur ❤️
        - Voir ses favoris : Cliquer sur 'Favoris' en haut de page (icône cœur)
        - Retirer des favoris : Cliquer à nouveau sur le cœur

        TYPES D'HÉBERGEMENT :
        - Maison, Appartement, Loft, Gîte, Chalet, Studio, Chambre d'hôte, Maison d'hôtes, Bungalow, Villa

        COMMODITÉS / ÉQUIPEMENTS :
        - Intérieur : WiFi gratuit, Télévision, Climatisation, Chauffage, Cuisine équipée, Lave-linge
        - Extérieur : Piscine, Jardin, Balcon, Terrasse, Parking gratuit
        - Chaque annonce liste ses équipements sur sa page

        PAIEMENT :
        - Paiement sécurisé par carte bancaire
        - Acompte à payer immédiatement (environ 45% du total)
        - Reste à payer sur place au propriétaire
        - Le paiement inclut : acompte location + frais de service + taxe de séjour

        PORTE-MONNAIE / SOLDE :
        - Visible sur la page Mon compte (encadré orange en haut à droite)
        - Affiche le solde disponible en euros

        AVIS / NOTES :
        - Chaque annonce affiche sa note moyenne (sur 5) et le nombre d'avis

        MESSAGERIE :
        - Contacter un propriétaire : depuis une annonce ou depuis une réservation (bouton 'Message')
        - Lors de la réservation : possibilité d'envoyer un message au propriétaire (heure d'arrivée, etc.)

        VÉRIFICATIONS :
        - E-mail : Vérifier son e-mail après inscription
        - Téléphone (SMS) : Recevoir un code et le valider
        - CNI : demander au moment de déposer une annonce si pas déjà fait

        INCIDENTS / LITIGES :
        - Signaler un incident : Mon compte → Réservations → Sélectionner la réservation → Cliquer sur 'Signaler un incident'
        - Décrire le problème et ajouter des preuves

        ANNULER UNE RÉSERVATION :
        - Mon compte → Réservations → Sélectionner la réservation → Cliquer sur 'Annuler'
        - Les conditions de remboursement dépendent de l'annonce

        PROBLÈMES TECHNIQUES :
        - Rafraîchir la page (F5)
        - Vider le cache du navigateur
        - Essayer un autre navigateur
        - Cliquez sur la page Aide
        ";


        $systemPrompt = "Tu es l'assistant virtuel du site Leboncoin Locations (location de logements vacances type Airbnb).
        CONTEXTE UTILISATEUR :
        {$userContext}
        {$knowledgeBase}
        TES RÈGLES :
        1. Réponds UNIQUEMENT en français, de manière concise et amicale (2-4 phrases max).
        2. Utilise la base de connaissances ci-dessus pour répondre précisément.
        3. Si l'utilisateur n'est pas connecté et veut réserver/déposer une annonce, dis-lui de se connecter d'abord.
        4. Si la question n'est pas liée au site, réponds poliment que tu ne peux aider que sur les sujets du site.
        5. N'invente jamais d'informations qui ne sont pas dans la base de connaissances.
        6. Sois chaleureux et utilise des emojis avec modération (1-2 max par réponse).
        7. Si on demande de l'aide générale, mentionne la page 'Aide' accessible dans le header.";

        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'max_tokens' => 400,
                    'temperature' => 0.6,
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                return response()->json(['reply' => 'Erreur API: ' . $data['error']['message']]);
            }

            $reply = $data['choices'][0]['message']['content'] ?? "Désolé, je n'ai pas compris. Pouvez-vous reformuler ?";

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Erreur: ' . $e->getMessage()]);
        }
    }
}
