<x-mail::message>
# Félicitations, votre annonce est en ligne !

Bonjour {{ $user->pseudonyme }},

Nous avons le plaisir de vous informer que votre annonce **« {{ $titreAnnonce }} »** a été validée par notre Service Immobilier.

Votre annonce est désormais visible par tous les utilisateurs de la plateforme.

<x-mail::button :url="route('user.annonces')">
Voir mes annonces
</x-mail::button>

Merci de votre confiance,<br>
{{ config('app.name') }}
</x-mail::message>
