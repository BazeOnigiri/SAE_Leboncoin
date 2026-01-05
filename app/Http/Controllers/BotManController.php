<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Models\Annonce;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('.*(bonjour|salut|hola|hello|wesh|coucou|bonsoir|hey|hi).*', function (BotMan $bot) {
            $currentHour = (int) date('H');

            $bot->typesAndWaits(1);

            if ($currentHour > 6 && $currentHour < 12) {
                $bot->reply('Bonjour, bonne matinée');
            } elseif ($currentHour < 18) {
                $bot->reply('Bonjour, bonne après-midi !');
            } else {
                $bot->reply('Bonsoir');
            }

            $bot->typesAndWaits(1);
            $bot->reply("Comment puis-je vous aider aujourd'hui ?");
        });

        $botman->hears('.*(inscription|inscrire|minscrire|m\'inscrire|creer|créer|creation|création).*(compte|account).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour créer un compte :<br>
                1) Cliquez sur <b>Connexion</b>.<br>
                2) Entrez votre e-mail.<br>
                3) Complétez le formulaire (profil, adresse, mot de passe...).<br>
                4) Validez puis vérifiez votre e-mail / téléphone.");
        });

        $botman->hears('.*(connexion|connecter|se connecter|login|déconnexion|deconnexion|logout).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour vous connecter :<br>
                1) Allez sur la page <b>Connexion</b>.<br>
                2) Entrez votre e-mail puis votre mot de passe.<br><br>
                Pour vous déconnecter :<br>
                • Allez dans <b>Mon compte</b> puis cliquez sur <b>Me déconnecter</b>.");
        });

        $botman->hears('.*(mot de passe.*oubli|mdp.*oubli|reinitialiser.*mot de passe|réinitialiser.*mot de passe|reset.*password|changer.*mot de passe|oublié.*mot de passe).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour réinitialiser votre mot de passe :<br>
                1) Cliquez sur <b>Connexion</b> puis <b>Mot de passe oublié</b>.<br>
                2) Vous recevez un lien par e-mail.<br>
                3) Suivez les instructions pour définir un nouveau mot de passe.");
        });

        $botman->hears('.*(services.*proposez|que faites-vous|vous faites quoi|tes services|quels services|aide|help|faq).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Voici ce que je peux faire :<br>
                1) Expliquer comment déposer une annonce (logement).<br>
                2) Expliquer comment réserver une annonce (dates, voyageurs).<br>
                3) Aider sur les paiements (acompte, carte bancaire).<br>
                4) Aider sur les vérifications (e-mail, SMS, CNI).<br>
                5) Aider sur votre compte (profil, favoris, annonces, réservations).<br>
                6) Informer sur les types d'hébergement et commodités.<br>
                7) Expliquer comment laisser un avis.<br><br>
                Dites-moi ce que vous voulez faire !");
        });

        $botman->hears('.*(guide|besoin.*aide|je suis perdu|je comprends pas|comment ça marche|comment ca marche).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si vous êtes perdu :<br>
                • Consultez la rubrique <b>Aide</b> dans le menu du site.<br>
                • Dites-moi ce que vous essayez de faire (déposer, réserver, paiement, vérification).");
        });

        $botman->hears('.*(deposer.*annonce|déposer.*annonce|publier.*annonce|mettre.*annonce|créer.*annonce|creer.*annonce|creation.*annonce|création.*annonce|nouvelle annonce).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour déposer une annonce :<br>
                1) Connectez-vous.<br>
                2) Allez sur <b>Déposer une annonce</b>.<br>
                3) Remplissez titre, description, prix, capacité, chambres, règles (animaux, fumeur...).<br>
                4) Ajoutez des photos si possible.<br>
                5) Validez.<br><br>
                Remarque : une vérification (SMS / modération) peut être demandée avant publication.");
        });

        $botman->hears('.*(mes annonces|voir.*mes annonces|gerer.*annonces|gérer.*annonces|modifier.*annonce|supprimer.*annonce).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour gérer vos annonces :<br>
                1) Allez dans <b>Mon compte</b> → <b>Annonces</b>.<br>
                2) Vous pouvez consulter, modifier ou suivre le statut.<br>
                3) Si une annonce demande une vérification SMS, vous verrez un bouton dédié.");
        });

        $botman->hears('.*(status.*annonce|statut.*annonce|annonce.*en attente|annonce.*refusée|annonce.*validée|validation.*annonce).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Le statut d'une annonce peut être :<br>
                • En attente de vérification (ex: SMS)<br>
                • En attente de validation (modération)<br>
                • Validée / publiée<br>
                • Refusée<br><br>
                Consultez <b>Mon compte → Annonces</b> pour voir le statut exact.");
        });

        $botman->hears('.*(pas trouver|ne trouve pas|je ne trouve pas|aucune annonce|aucun résultat|aucun resultat|destination).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Si aucune annonce ne s'affiche :<br>
                1) Vérifiez les dates choisies.<br>
                2) Essayez une autre destination ou élargissez la zone.<br>
                3) Retirez certains filtres (prix, capacité…).");
        });

        $botman->hears('.*(rechercher|recherche|trouver.*annonce|filtrer|filtres|chercher).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour rechercher une annonce :<br>
                1) Utilisez la barre de recherche (ville, dates, voyageurs).<br>
                2) Appliquez des filtres : prix, type de logement, capacité, chambres, commodités.<br>
                3) Ouvrez une annonce pour voir les détails et réserver.");
        });

        $botman->hears('.*(disponibilite|disponibilité|dates disponibles|calendrier|dispo|disponible).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour voir les disponibilités :<br>
                1) Ouvrez l'annonce qui vous intéresse.<br>
                2) Consultez le calendrier des disponibilités.<br>
                3) Les dates disponibles sont sélectionnables, les autres sont grisées.<br>
                4) Sélectionnez vos dates d'arrivée et de départ.");
        });

        $botman->hears('.*(type.*logement|type.*hébergement|type.*hebergement|types.*logement|maison|appartement|loft|gite|gîte|chalet|studio|bungalow|chambre.*hote|chambre.*hôte).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Types d'hébergement disponibles :<br>
                • Maison<br>
                • Appartement<br>
                • Loft<br>
                • Gîte<br>
                • Chalet<br>
                • Studio<br>
                • Chambre d'hôte<br>
                • Maison d'hôtes<br>
                • Bungalow<br><br>
                Utilisez le filtre <b>Type d'hébergement</b> pour affiner votre recherche.");
        });

        $botman->hears('.*(commodite|commodité|commodités|équipement|equipement|équipements|wifi|piscine|parking|climatisation|cuisine|jardin|terrasse|lave-linge|télévision|television).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Commodités et équipements :<br>
                • Chaque annonce liste ses équipements (WiFi, piscine, parking, climatisation, etc.).<br>
                • Utilisez les filtres de recherche pour trouver des logements avec des commodités spécifiques.<br>
                • Les commodités sont visibles sur la page de l'annonce.");
        });

        $botman->hears('.*(prix|tarif|cout|coût|combien.*coute|combien.*coûte|cher|pas cher|budget|tarification).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Prix des annonces :<br>
                • Chaque annonce affiche son <b>prix par nuit</b>.<br>
                • Utilisez le filtre <b>Prix</b> pour définir un budget min/max.<br>
                • Le prix total dépend du nombre de nuits.<br>
                • Certaines annonces peuvent demander un acompte.");
        });

        $botman->hears('.*(réserver|reserver|faire.*reservation|faire.*réservation|créer.*reservation|créer.*réservation|reservation|réservation|booking|résa|resa).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour réserver un séjour :<br>
                1) Ouvrez l'annonce.<br>
                2) Choisissez vos dates (début/fin) et le nombre de voyageurs.<br>
                3) Cliquez sur <b>Réserver</b>.<br>
                4) Confirmez les informations demandées.<br>
                5) Procédez au paiement si l'annonce l'exige.");
        });

        $botman->hears('.*(ou voir.*mes reservations|où voir.*mes reservations|mes reservations|mes réservations|voir.*reservations|voir.*réservations|suivre.*reservation|suivre.*réservation|historique.*reservation).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour voir vos réservations :<br>
                1) Allez dans <b>Mon compte</b> → <b>Réservations</b>.<br>
                2) Vous verrez vos séjours à venir et passés.<br>
                3) Cliquez sur une réservation pour voir les détails.");
        });

        $botman->hears('.*(annuler.*reservation|annuler.*réservation|annulation.*reservation|annulation).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour annuler une réservation :<br>
                1) Allez dans <b>Mon compte</b> → <b>Réservations</b>.<br>
                2) Ouvrez la réservation concernée.<br>
                3) Cliquez sur <b>Annuler</b> si l'option est disponible.<br><br>
                Remarque : les remboursements dépendent des conditions de l'annonce.");
        });

        $botman->hears('.*(avis|note|noter|laisser.*avis|donner.*avis|évaluation|evaluation|commentaire|etoiles|étoiles).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour laisser un avis :<br>
                1) Vous devez avoir effectué un séjour.<br>
                2) Allez dans <b>Mon compte → Réservations</b>.<br>
                3) Cliquez sur <b>Laisser un avis</b> sur la réservation terminée.<br>
                4) Notez (étoiles) et commentez votre expérience.<br><br>
                Les avis aident les autres voyageurs à choisir !");
        });

        $botman->hears('.*(contacter.*proprietaire|contacter.*propriétaire|envoyer.*message|messagerie|ecrire.*proprietaire|écrire.*propriétaire|discussion|message.*proprietaire|message.*propriétaire).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour contacter un propriétaire :<br>
                1) Ouvrez l'annonce qui vous intéresse.<br>
                2) Cliquez sur <b>Contacter le propriétaire</b>.<br>
                3) Rédigez votre message et envoyez.<br>
                4) Retrouvez vos conversations dans <b>Mon compte → Messages</b>.");
        });

        $botman->hears('.*(acompte.*obligatoire|faut.*acompte|payer.*acompte).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Acompte :<br>
                • Certaines annonces exigent un acompte (montant fixe ou pourcentage).<br>
                • D'autres non.<br>
                • L'information est indiquée sur l'annonce avant de réserver.");
        });

        $botman->hears('.*(probleme|problème|erreur|bug).*(paiement|payer|carte|cb).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Problème de paiement :<br>
                1) Vérifiez que votre carte est valide et que votre solde/limite le permet.<br>
                2) Essayez une autre carte.<br>
                3) Rafraîchissez la page et réessayez.<br>
                4) Si ça persiste, contactez le support.");
        });

        $botman->hears('.*(paiement|payer|carte bancaire|cb|facture|transaction).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Paiement :<br>
                • Certaines annonces demandent un <b>acompte</b>.<br>
                • Le paiement se fait par carte bancaire.<br>
                • Vous recevrez une confirmation par e-mail.<br><br>
                Si vous avez une erreur, dites <b>problème paiement</b>.");
        });

        $botman->hears('.*(solde|porte-monnaie|porte monnaie|portefeuille|mon argent|mes gains|mon solde).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour voir votre solde :<br>
                1) Allez dans <b>Mon compte</b>.<br>
                2) Votre solde est affiché dans la section <b>Porte-monnaie</b>.<br>");
        });

        $botman->hears('.*(verification.*email|vérification.*email|verif.*mail|vérif.*mail|mail.*verification|renvoyer.*email|pas recu.*mail|pas reçu.*mail|email.*pas recu|confirmer.*email).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vérification e-mail :<br>
                1) Allez sur la page <b>Vérification e-mail</b>.<br>
                2) Cliquez sur <b>Renvoyer l'e-mail</b>.<br>
                3) Vérifiez vos spams.<br><br>
                Tant que l'e-mail n'est pas vérifié, certaines actions peuvent être bloquées.");
        });

        $botman->hears('.*(verification.*tel|vérification.*tel|verification.*telephone|vérification.*telephone|verif.*sms|vérif.*sms|code.*sms|pas recu.*sms|pas reçu.*sms|renvoyer.*sms|confirmer.*telephone).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Vérification téléphone (SMS) :<br>
                1) Vérifiez votre numéro dans <b>Mon compte</b>.<br>
                2) Sur l'écran de vérification, cliquez <b>Renvoyer un code</b>.<br>
                3) Entrez le code reçu (il expire après quelques minutes).");
        });

        $botman->hears('.*(un seul compte.*telephone|un seul compte.*téléphone|unique.*telephone|telephone.*unique|numéro.*déjà utilisé|numero.*deja utilisé).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Numéro de téléphone unique :<br>
                • Un numéro déjà utilisé ne peut pas créer un second compte.<br>
                • Connectez-vous au compte existant ou utilisez un autre numéro.<br>
                • Si c'est une erreur, contactez le support.");
        });

        $botman->hears('.*(comment|faire|mettre|envoyer).*(cni|carte.*identite|carte.*d\'identité|piece.*identite|pièce.*identité|verification.*identite|vérification.*identité).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour vérifier votre identité (CNI) :<br>
                1) Allez dans <b>Mon compte → CNI</b>.<br>
                2) Déposez les documents demandés (photo/scan lisible).<br>
                3) Validez l'envoi et attendez la validation par l'équipe.");
        });

        $botman->hears('.*(a quoi sert|pourquoi).*(cni|identite|identité|verification.*identite|vérification.*identité).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("La vérification d'identité (CNI) sert à :<br>
                • Sécuriser les échanges.<br>
                • Limiter les fraudes et faux comptes.<br>
                • Autoriser des actions sensibles (réservation/paiement/dépôt selon règles).");
        });

        $botman->hears('.*(incident|litige|probleme.*reservation|problème.*réservation|signaler.*incident|signaler.*probleme|signaler.*problème|reclamation|réclamation).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Signaler un incident :<br>
                1) Allez dans <b>Mon compte</b> → <b>Réservations</b>.<br>
                2) Ouvrez la réservation concernée.<br>
                3) Cliquez sur <b>Signaler un incident</b> (si disponible).<br>
                4) Décrivez le problème et ajoutez des preuves si demandé.");
        });

        $botman->hears('.*(mon compte|mon profil|profil|parametres|paramètres|infos privées|informations|modifier.*profil).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour gérer votre compte :<br>
                • <b>Mon compte</b> : vue générale (profil + solde).<br>
                • <b>Profil</b> : modifier votre profil public.<br>
                • <b>Paramètres</b> : infos privées (e-mail, téléphone).<br>
                • <b>Connexion et sécurité</b> : mot de passe, double authentification.");
        });

        $botman->hears('.*(modifier.*telephone|changer.*telephone|modifier.*email|changer.*email|modifier.*numero|changer.*numero).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour modifier votre e-mail ou téléphone :<br>
                1) Connectez-vous.<br>
                2) Allez dans <b>Mon compte → Paramètres</b>.<br>
                3) Mettez à jour vos infos et enregistrez.");
        });

        $botman->hears('.*(favori|favoris|sauvegarder.*annonce|enregistrer.*annonce).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Favoris :<br>
                1) Ouvrez une annonce.<br>
                2) Cliquez sur l'icône <b>cœur</b> pour l'ajouter aux favoris.<br>
                3) Retrouvez la liste dans <b>Mon compte → Favoris</b>.");
        });

        $botman->hears('.*(problème.*chargement|probleme.*chargement|chargement.*page|la page.*charge pas|la page.*ne charge pas|bug|erreur|ne marche pas|ne fonctionne pas).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Problème technique :<br>
                1) Vérifiez votre connexion internet.<br>
                2) Rafraîchissez la page (F5 ou Ctrl+R).<br>
                3) Videz le cache de votre navigateur.<br>
                4) Essayez un autre navigateur.<br>
                5) Si ça persiste, contactez le support.");
        });

        $botman->hears('.*(service client|assistance|support technique|support$|formulaire.*contact|joindre.*support|aide.*technique).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Pour contacter le support :<br>
                1) Utilisez le formulaire de contact en bas de page.<br>
                2) Décrivez votre problème (page, action, message d'erreur).<br>
                3) Ajoutez une capture d'écran si possible.<br>
                4) Nous vous répondrons dans les plus brefs délais.");
        });

        $botman->hears('.*(combien.*annonces|statistiques.*annonces|il y a combien.*annonces|nombre.*annonces).*', function (BotMan $bot) {
            $count = Annonce::count();
            $bot->typesAndWaits(2);
            $bot->reply("Actuellement, il y a <b>" . $count . " annonces</b> en ligne sur le site.");
        });

        $botman->hears('.*(securite|sécurité|double authentification|2fa|authentification.*deux.*facteurs).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Sécurité du compte :<br>
                1) Allez dans <b>Mon compte → Connexion et sécurité</b>.<br>
                2) Vous pouvez changer votre mot de passe.<br>
                3) Activez la <b>double authentification (2FA)</b> pour plus de sécurité.");
        });

        $botman->hears('.*(animaux|animal|chien|chat|accepte.*animaux|fumeur|fumer|cigarette|non-fumeur).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Animaux et fumeurs :<br>
                • Chaque annonce indique si les animaux sont acceptés ou non.<br>
                • Chaque annonce indique si le logement est fumeur ou non-fumeur.<br>
                • Utilisez les filtres pour trouver un logement adapté à vos besoins.");
        });

        $botman->hears('.*(capacite|capacité|combien.*personnes|combien.*voyageurs|nombre.*personnes|nombre.*voyageurs|adultes|enfants|bebes|bébés).*', function (BotMan $bot) {
            $bot->typesAndWaits(2);
            $bot->reply("Capacité des logements :<br>
                • Chaque annonce indique la capacité maximale (adultes, enfants, bébés).<br>
                • Utilisez le filtre <b>Nombre de voyageurs</b> dans la recherche.<br>
                • Respectez la capacité indiquée lors de la réservation.");
        });

        $botman->hears('.*(merci|thx|thanks|super|parfait|genial|génial|top).*', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply("Avec plaisir 🙂 N'hésitez pas si vous avez d'autres questions !");
        });

        $botman->hears('.*(Damas|Luc Damas|M\.Damas).*', function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply("Bonjour Monsieur Damas, bienvenue sur notre site 🧡");
        });

        $botman->fallback(function (BotMan $bot) {
            $bot->typesAndWaits(1);
            $bot->reply("Désolé, je ne comprends pas votre demande. Pouvez-vous reformuler ?");
            $bot->typesAndWaits(1);
            $bot->reply("Je peux vous aider sur :<br>
                • Créer un compte / Se connecter<br>
                • Déposer une annonce<br>
                • Rechercher / Filtrer des annonces<br>
                • Réserver un logement<br>
                • Paiement / Acompte<br>
                • Vérification e-mail / SMS / CNI<br>
                • Mon compte / Profil<br>
                • Avis / Notes<br>
                • Messagerie<br>
                • Support technique");
        });

        $botman->listen();
    }
}