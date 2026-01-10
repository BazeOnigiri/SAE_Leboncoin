@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
            <h1 class="text-3xl font-bold mb-6 text-gray-900">Politique d'utilisation des cookies</h1>
            <p class="text-sm text-gray-500 mb-8">Mise à jour le {{ now()->format('d/m/Y') }}</p>

            <section class="mb-8">
                <p class="text-gray-700 mb-4">
                    Chez <strong>Leboncoin</strong>, nous accordons une grande importance à la transparence. Cette page vous explique
                    comment nous utilisons les cookies et autres traceurs pour améliorer votre expérience sur notre plateforme de
                    location saisonnière.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-900">1. Qu'est-ce qu'un cookie ?</h2>
                <p class="text-gray-700">
                    Un cookie est un petit fichier texte enregistré sur votre appareil (ordinateur, tablette, smartphone) lorsque vous
                    visitez un site. Il permet de conserver des données utilisateur afin de faciliter la navigation et de permettre
                    certaines fonctionnalités.
                </p>
            </section>

            <section class="mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-900">2. Vos choix</h2>
                <div class="bg-orange-50 border border-orange-100 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-orange-900 mb-2">Gérez vos préférences à tout moment</h3>
                        <p class="text-orange-800 text-sm">
                            Vous pouvez modifier vos consentements pour les fonctionnalités non essentielles (cartes interactives, chat) via notre panneau de configuration.
                        </p>
                    </div>
                    <!-- Open Settings Modal Button -->
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-cookie-settings'))" 
                        class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg transition-colors whitespace-nowrap">
                        Gérer mes cookies
                    </button>
                </div>
            </section>

            <section class="mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-900">3. Liste détaillée des cookies</h2>

                <!-- Category A -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-2 py-1 rounded">Nécessaire</span>
                        <h3 class="text-lg font-semibold">Cookies Techniques & Fonctionnels (Strictement Nécessaires)</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Ces cookies sont indispensables au bon fonctionnement du site et à sa sécurité. Ils ne requièrent pas votre consentement préalable.
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Finalité</th>
                                    <th scope="col" class="px-6 py-3">Fournisseur</th>
                                    <th scope="col" class="px-6 py-3">Conservation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">leboncoin_session</th>
                                    <td class="px-6 py-4">Maintien de votre session de connexion.</td>
                                    <td class="px-6 py-4">Leboncoin (Interne)</td>
                                    <td class="px-6 py-4">Session</td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">XSRF-TOKEN</th>
                                    <td class="px-6 py-4">Sécurisation des formulaires contre les attaques CSRF.</td>
                                    <td class="px-6 py-4">Leboncoin (Interne)</td>
                                    <td class="px-6 py-4">2 heures</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category B (Functional / Preferences) -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">Fonctionnel</span>
                        <h3 class="text-lg font-semibold">Cookies de Préférences</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Ces cookies permettent d'activer des fonctionnalités demandées explicitement (ex: "Se souvenir de moi").
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Finalité</th>
                                    <th scope="col" class="px-6 py-3">Fournisseur</th>
                                    <th scope="col" class="px-6 py-3">Conservation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">remember_web_*</th>
                                    <td class="px-6 py-4">Permet de rester connecté automatiquement si vous avez coché "Se souvenir de moi".</td>
                                    <td class="px-6 py-4">Leboncoin (Interne)</td>
                                    <td class="px-6 py-4">5 ans (ou jusqu'à déconnexion manuelle)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Category C (Analytics - Empty for now) -->
                <!-- 
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-blue-200 text-blue-800 text-xs font-bold px-2 py-1 rounded">Statistiques</span>
                        <h3 class="text-lg font-semibold">Cookies de Mesure d'Audience</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Aucun cookie de mesure d'audience n'est actuellement déployé sur ce site.
                    </p>
                </div>
                -->

                <!-- Category D (Third Party) -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded">Tiers</span>
                        <h3 class="text-lg font-semibold">Cookies Tiers et Fonctionnalités Avancées</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">
                        Ces cookies sont liés à des services externes intégrés à notre site. Ils nécessitent votre consentement.
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Finalité</th>
                                    <th scope="col" class="px-6 py-3">Fournisseur</th>
                                    <th scope="col" class="px-6 py-3">Conservation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">NID / CONSENT</th>
                                    <td class="px-6 py-4">Affichage des cartes interactives pour localiser les biens.</td>
                                    <td class="px-6 py-4">Google Maps</td>
                                    <td class="px-6 py-4">6 mois</td>
                                </tr>
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Stockage Local (Chat)</th>
                                    <td class="px-6 py-4">Gestion de la conversation avec l'assistant virtuel.</td>
                                    <td class="px-6 py-4">Leboncoin (Interne)</td>
                                    <td class="px-6 py-4">Persistant</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>

            <section>
                <h2 class="text-xl font-bold mb-4 text-gray-900">4. Vos droits sur vos données</h2>
                <div class="bg-gray-900 text-white rounded-xl p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-bold mb-4">Exercer vos droits</h3>
                            <ul class="space-y-2 text-gray-300">
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Droit d'accès
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Rectification
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Effacement (Oubli)
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Portabilité
                                </li>
                            </ul>
                        </div>
                        <div class="flex flex-col justify-center items-start">
                             <p class="mb-4 text-gray-300">
                                Pour toute question relative à cette politique ou pour exercer vos droits, vous pouvez contacter notre Délégué à la Protection des Données (DPO).
                             </p>
                             <a href="mailto:dpo@leboncoin.fr" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                Exercer mes droits par email
                             </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
