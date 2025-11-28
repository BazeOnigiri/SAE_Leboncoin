<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\RegisterController;

// --------------------------------------------------------------
// PAGE D'ACCUEIL
// --------------------------------------------------------------
Route::get('/', [AnnonceController::class, 'index'])->name('home');
Route::get('/annonce/{id}', [AnnonceController::class, 'view'])->name('annonce.view');

// --------------------------------------------------------------
// WIZARD INSCRIPTION 
// --------------------------------------------------------------

// email + mot de passe (affichage)
Route::get('/register', [RegisterController::class, 'showEmailForm'])
    ->middleware('guest')
    ->name('register');

// traitement
Route::post('/register/email', [RegisterController::class, 'storeEmail'])
    ->middleware('guest')
    ->name('register.email');

// choix du type de compte
Route::get('/register/type', [RegisterController::class, 'showTypeForm'])
    ->middleware('guest')
    ->name('register.type');
Route::post('/register/type', [RegisterController::class, 'storeType'])
    ->middleware('guest');

// Particulier
Route::get('/register/particulier', [RegisterController::class, 'showParticulierForm'])
    ->middleware('guest')
    ->name('register.particulier');
Route::post('/register/particulier', [RegisterController::class, 'storeParticulier'])
    ->middleware('guest');

// Professionnel
Route::get('/register/professionnel', [RegisterController::class, 'showProForm'])
    ->middleware('guest')
    ->name('register.pro');
Route::post('/register/professionnel', [RegisterController::class, 'storePro'])
    ->middleware('guest');

// --------------------------------------------------------------
// ROUTES PROTÉGÉES (JETSTREAM PAR DÉFAUT)
// --------------------------------------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});
