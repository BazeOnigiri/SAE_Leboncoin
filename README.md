# Usage
# Docker
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
php artisan migrate:fresh
```
Dans les deux cas il faut ajouter les inserts ensuite :
```
php artisan db:seed
```
Faire clique droit sur la DB --> Refresh dans PgAdmin4 pour voir les changements.

## User from debit seed
email: ```test@example.com```
password: ```password```

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
