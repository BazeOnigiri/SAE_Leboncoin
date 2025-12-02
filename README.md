## Usage
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
# DB conf
Setup tables : 
```
php artisan migrate
```
Add inserts
```
php artisan db:seed
```

# User from debit seed
email: ```test@example.com```
password: ```password```
