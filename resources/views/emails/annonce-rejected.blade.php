<x-mail::message>
# Vérification d'identité rejetée

Bonjour {{ $user->pseudonyme }},

Nous avons le regret de vous informer que votre demande de vérification d'identité a été rejetée.

Les documents fournis n'ont pas pu être validés. Cela peut être dû à :
- Une photo floue ou illisible
- Un document non conforme ou expiré
- Des informations incohérentes

Vous pouvez soumettre à nouveau vos documents en vous rendant sur votre espace personnel.

<x-mail::button :url="route('cni.index')">
Soumettre à nouveau
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
