@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-6 md:px-12 xl:px-6">
        
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Comment pouvons-nous vous aider ?</h1>
            <p class="text-gray-600">Retrouvez ici les réponses aux questions les plus fréquentes.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Sidebar: Questions Fréquentes -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 sticky top-24">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Questions fréquentes</h2>
                </div>
                
                <ul class="space-y-4">
                    <li>
                        <a href="#faq-compte-creation" class="block text-gray-600 hover:text-orange-600 hover:translate-x-1 transition-all">
                            › Comment créer un compte ?
                        </a>
                    </li>
                    <li>
                        <a href="#faq-annonce-depot" class="block text-gray-600 hover:text-orange-600 hover:translate-x-1 transition-all">
                            › Comment déposer une annonce ?
                        </a>
                    </li>
                    <li>
                        <a href="#faq-paiement-frais" class="block text-gray-600 hover:text-orange-600 hover:translate-x-1 transition-all">
                            › Quels sont les frais de service ?
                        </a>
                    </li>
                    <li>
                        <a href="#faq-securite-arnaque" class="block text-gray-600 hover:text-orange-600 hover:translate-x-1 transition-all">
                            › Comment signaler une arnaque ?
                        </a>
                    </li>
                    <li>
                        <a href="#faq-reservation-suivi" class="block text-gray-600 hover:text-orange-600 hover:translate-x-1 transition-all">
                            › Comment suivre ma réservation ?
                        </a>
                    </li>
                     <li>
                        <a href="#faq-compte-mdp" class="block text-gray-600 hover:text-orange-600 hover:translate-x-1 transition-all">
                            › J'ai oublié mon mot de passe
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content: Accordions -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section 1: Mon Compte -->
                <div x-data="{ open: false }" 
                     x-init="if(location.hash && $el.querySelector(location.hash)) open = true"
                     @hashchange.window="if(location.hash && $el.querySelector(location.hash)) open = true"
                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="compte">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Mon Compte</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-6 pb-6 space-y-4">
                            <div id="faq-compte-creation" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment créer un compte ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Cliquez sur "Se connecter" en haut à droite, puis suivez les instructions pour créer un compte. Vous aurez besoin d'une adresse email valide et de définir un mot de passe sécurisé.</p>
                            </div>
                            <div id="faq-compte-mdp" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">J'ai oublié mon mot de passe</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Sur la page de connexion, cliquez sur "Mot de passe oublié". Entrez votre email pour recevoir un lien de réinitialisation.</p>
                            </div>
                             <div id="faq-compte-modif" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment modifier mes informations personnelles ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Rendez-vous dans votre tableau de bord, section "Profil" ou "Paramètres" pour modifier votre nom, email ou photo de profil.</p>
                            </div>
                            <div id="faq-compte-suppression" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment supprimer mon compte ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Pour supprimer votre compte et toutes vos données, rendez-vous dans "Paramètres" > "Sécurité" et cliquez sur "Supprimer mon compte". Attention, cette action est irréversible.</p>
                            </div>
                            <div id="faq-compte-notif" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Gérer mes notifications</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Vous pouvez choisir quels emails ou notifications recevoir depuis la section "Paramètres" de votre compte.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Annonces -->
                <div x-data="{ open: false }" 
                     x-init="if(location.hash && $el.querySelector(location.hash)) open = true"
                     @hashchange.window="if(location.hash && $el.querySelector(location.hash)) open = true"
                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="annonce">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Déposer & Gérer mes annonces</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-6 pb-6 space-y-4">
                            <div id="faq-annonce-depot" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment déposer une annonce ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Connectez-vous et cliquez sur le bouton "Déposer une annonce" en haut de page. Remplissez le formulaire avec un titre précis, une description détaillée et ajoutez des photos attractives.</p>
                            </div>
                             <div id="faq-annonce-modif" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Modifier ou supprimer une annonce</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Depuis votre tableau de bord, cliquez sur "Mes annonces". Vous pourrez alors éditer le contenu ou supprimer l'annonce si le bien est vendu.</p>
                            </div>
                            <div id="faq-annonce-refus" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Mon annonce a été refusée, pourquoi ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Une annonce peut être refusée si elle ne respecte pas nos conditions générales d'utilisation (produit interdit, photos inappropriées, description trompeuse). Vérifiez l'email reçu pour le motif précis.</p>
                            </div>
                            <div id="faq-annonce-prix" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Combien coûte le dépôt d'une annonce ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Le dépôt d'annonce est gratuit pour les particuliers. Des options de mise en avant payantes peuvent vous être proposées pour augmenter la visibilité de votre bien.</p>
                            </div>
                            <div id="faq-annonce-duree" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Combien de temps mon annonce reste-t-elle en ligne ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Votre annonce reste en ligne pendant 60 jours. Vous recevrez un email avant son expiration pour la renouveler gratuitement.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Messagerie & Protection -->
                <div x-data="{ open: false }" 
                     x-init="if(location.hash && $el.querySelector(location.hash)) open = true"
                     @hashchange.window="if(location.hash && $el.querySelector(location.hash)) open = true"
                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="messagerie">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Messagerie & Échanges</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-6 pb-6 space-y-4">
                            <div id="faq-messagerie-envoi" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment envoyer un message ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Sur la page de l'annonce, cliquez sur "Envoyer un message". Une conversation sécurisée s'ouvrira alors avec le vendeur.</p>
                            </div>
                             <div id="faq-messagerie-pj" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Puis-je envoyer des pièces jointes ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Oui, vous pouvez envoyer des photos ou des documents PDF via la messagerie sécurisée.</p>
                            </div>
                            <div id="faq-messagerie-bloquer" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment bloquer un utilisateur ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Si un utilisateur vous importune, ouvrez la conversation et cliquez sur les options (trois points) puis "Bloquer l'utilisateur".</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Réservations & Paiements -->
                <div x-data="{ open: false }" 
                     x-init="if(location.hash && $el.querySelector(location.hash)) open = true"
                     @hashchange.window="if(location.hash && $el.querySelector(location.hash)) open = true"
                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="reservation">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                         <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Réservations & Paiements</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-6 pb-6 space-y-4">
                           <div id="faq-paiement-securise" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment fonctionne le paiement sécurisé ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">L'acheteur paie en ligne sur notre plateforme. L'argent est bloqué jusqu'à ce que la transaction soit finalisée (livraison ou remise en main propre confirmée). Cela protège les deux parties.</p>
                            </div>
                             <div id="faq-paiement-frais" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Quels sont les frais de service ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Des frais de protection acheteur s'appliquent sur chaque transaction sécurisée. Ils couvrent l'assistance en cas de litige et la sécurisation du paiement.</p>
                            </div>
                            <div id="faq-reservation-suivi" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment suivre ma réservation ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Retrouvez toutes vos réservations en cours dans "Mon compte" > "Mes réservations". Vous y verrez l'état d'avancement et pourrez contacter le vendeur.</p>
                            </div>
                             <div id="faq-avis" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment laisser un avis ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Une fois la transaction terminée, vous recevrez un email vous invitant à noter le vendeur. Votre avis sera visible sur son profil public.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Sécurité -->
                <div x-data="{ open: false }" 
                     x-init="if(location.hash && $el.querySelector(location.hash)) open = true"
                     @hashchange.window="if(location.hash && $el.querySelector(location.hash)) open = true"
                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="securite">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                         <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Sécurité & Confiance</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-6 pb-6 space-y-4">
                           <div id="faq-securite-arnaque" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment reconnaître une tentative de fraude ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Méfiez-vous des vendeurs demandant des paiements hors plateforme (Western Union, coupons, etc.) ou communiquant uniquement par email personnel. Ne donnez jamais vos identifiants bancaires.</p>
                            </div>
                            <div id="faq-securite-signalement" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Comment signaler un utilisateur ou une annonce ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Sur chaque page d'annonce ou de profil, vous trouverez un bouton "Signaler". Nos équipes modèrent les contenus signalés 7j/7.</p>
                            </div>
                            <div id="faq-securite-verif" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Vérification d'identité</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Pour plus de sécurité, certains vendeurs ont un badge "Identité vérifiée". Cela signifie qu'ils ont transmis leur pièce d'identité à notre service de modération.</p>
                            </div>
                        </div>
                    </div>
                </div>

                 <!-- Section 6: Livraison -->
                <div x-data="{ open: false }" 
                     x-init="if(location.hash && $el.querySelector(location.hash)) open = true"
                     @hashchange.window="if(location.hash && $el.querySelector(location.hash)) open = true"
                     class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" id="livraison">
                    <button @click="open = !open" class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors">
                         <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Livraison</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse style="display: none;">
                        <div class="px-6 pb-6 space-y-4">
                           <div id="faq-livraison-modes" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Quels sont les modes de livraison disponibles ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Nous travaillons avec plusieurs transporteurs (Mondial Relay, Colissimo, etc.). Le choix dépend du poids de l'objet et des préférences du vendeur.</p>
                            </div>
                            <div id="faq-livraison-frais" class="pt-4 border-t border-gray-100 target:bg-orange-50 target:p-4 target:rounded-lg transition-all scroll-mt-32">
                                <h4 class="font-semibold text-gray-900 mb-2">Qui paie les frais de port ?</h4>
                                <p class="text-gray-600 text-sm leading-relaxed">Les frais de port sont généralement à la charge de l'acheteur et sont payés lors de la commande.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
