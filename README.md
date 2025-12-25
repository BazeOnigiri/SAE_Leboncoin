# Usage

## Docker

```
docker build -t laravel-app .
```

```
docker run -p 9000:9000 laravel-app
```

## Github conf

```
git config --global user.email "you@example.com"
```

```
git config --global user.name "Your Name"
```

## DB conf

Créer la DB pour la première fois :

```
php artisan migrate
```

Ou mettre à jour la DB :
```

php artisan migrate:fresh --seed

```
Faire clique droit sur la DB --> Refresh dans PgAdmin4 pour voir les changements.


## Pour que le navigateur puisse charger les images :
```

php artisan storage:link

```
## Cache config
Charger les markers sur la map
```

php artisan geo:cache

```
Vider le cache
```

php artisan geo:clear

```

## Comptes

| Rôle | Email | Mot de passe |
| --- | --- | --- |
| Utilisateur test (sans rôle) | test@example.com | passwordT67! |
| Super Admin | super_admin@example.com | password |
| Service Petite Annonce | service_petite_annonce@example.com | password |
| Directeur Service Petite Annonce | directeur_service_petite_annonce@example.com | password |
| Service Immobilier | service_imobilier@example.com | password |
| Directeur Service Immobilier | directeur_service_imobilier@example.com | password |
| Service Inscription | service_inscription@example.com | password |
| Directeur Service Inscription | directeur_service_inscription@example.com | password |
| Service Location | service_location@example.com | password |
| Directeur Service Location | directeur_service_location@example.com | password |

# Doc
## Allerts

need a view with app layout

success exemple :
```php
return back()->with('success', 'Votre CNI est déjà vérifiée.');
```

error exemple :

```php
return back()->withErrors(['error' => 'Une erreur est survenue.']);
```

info exemple :

```php
return back()->with('info', 'Votre CNI est déjà vérifiée.');
```
