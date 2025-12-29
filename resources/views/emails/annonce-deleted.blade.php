<x-mail::message>
# Annonce rejetée

Bonjour {{ $user->pseudonyme }},

Nous avons le regret de vous informer que votre annonce **« {{ $titreAnnonce }} »** n'a pas été validée par notre Service Immobilier et a été supprimée.

@if($motif)
**Motif du rejet :** {{ $motif }}
@else
Les raisons possibles de ce rejet peuvent être :
- Informations incomplètes ou incorrectes
- Photos non conformes ou de mauvaise qualité
- Description ne respectant pas nos conditions d'utilisation
- Bien ne correspondant pas aux critères de la plateforme
@endif

Vous pouvez soumettre une nouvelle annonce en tenant compte de ces éléments.

<x-mail::button :url="route('annonce.create')">
Créer une nouvelle annonce
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
