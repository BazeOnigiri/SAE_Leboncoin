<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AnnonceVerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\CNIController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\ServicePetiteAnnonceController;
use App\Http\Controllers\DevController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\ServiceInscriptionController;

if (app()->environment('local')) {
    Route::post('/dev/login-as', [DevController::class, 'loginAs'])->name('dev.login-as');
    Route::post('/dev/create-user', [DevController::class, 'createUser'])->name('dev.create-user');
    Route::post('/dev/create-annonce', [DevController::class, 'createAnnonce'])->name('dev.create-annonce');
    Route::post('/dev/create-cni', [DevController::class, 'createCni'])->name('dev.create-cni');
}


Route::get('/annonce/{id}', [AnnonceController::class, 'view'])->name('annonce.view');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.profile');



Route::get('/connexion', [ConnexionController::class, 'showEmailForm'])
    ->middleware('guest')
    ->name('auth.check');

Route::post('/connexion', [ConnexionController::class, 'checkEmail'])
    ->middleware('guest')
    ->name('auth.verify');

Route::middleware([
    'user.only'
])->group(function () {
    Route::get('/', [AnnonceController::class, 'index'])->name('home');

    Route::get('/check-reservation/{id}', function ($id) {
        return redirect()->route('annonce.view', ['id' => $id]);
    })->middleware(['auth', 'verified'])->name('check.reservation');
    Route::get('/reservation/creer/{id}', [ReservationController::class, 'create'])
        ->middleware(['auth', 'verified'])
        ->name('reservation.create');
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/reservations/{reservation}/incident/signaler', [IncidentController::class, 'create'])
            ->name('incidents.create');

        Route::post('/reservations/{reservation}/incident', [IncidentController::class, 'store'])
            ->name('incidents.store');
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        return view('dashboard', ['user' => $user]);
    })->name('dashboard');

    Route::middleware(['user.only'])->group(function () {

        Route::get('/deposer-une-annonce', [AnnonceController::class, 'create'])->name('annonce.create');
        Route::post('/annonce', [AnnonceController::class, 'store'])->name('annonce.store');

        Route::get('/annonce/{annonce}/verify-sms', [AnnonceVerificationController::class, 'show'])->where('annonce', '[0-9]+')->name('annonce.verify-sms');
        Route::post('/annonce/{annonce}/verify-sms', [AnnonceVerificationController::class, 'verify'])->where('annonce', '[0-9]+')->name('annonce.verify-sms.submit');
        Route::post('/annonce/{annonce}/verify-sms/resend', [AnnonceVerificationController::class, 'resend'])->where('annonce', '[0-9]+')->name('annonce.verify-sms.resend');

        Route::get('/cni', [CNIController::class, 'index'])->name('cni.index');
        Route::post('/cni', [CNIController::class, 'store'])->name('cni.store');
        Route::get('/compte/profil/modifier', [UserAccountController::class, 'edit'])->name('user.edit');
        Route::get('/compte/mes-annonces', [UserAccountController::class, 'annonces'])->name('user.annonces');
        Route::get('/compte/profil-espaces', [UserAccountController::class, 'spaces'])->name('user.spaces');
        Route::get('/compte/connexion-securite', [UserAccountController::class, 'security'])->name('user.security');
        Route::get('/compte/parametres', [UserAccountController::class, 'settings'])->name('user.settings');
        Route::post('/compte/parametres', [UserAccountController::class, 'updateSettings'])->name('user.settings.update');

        Route::get('/compte/mes-reservations', [ReservationController::class, 'mesReservations'])->name('user.mes-reservations');

        Route::get('/favorites', [UserAccountController::class, 'favorites'])->name('user.favorites');
        Route::post('/favorites/toggle', [UserAccountController::class, 'toggleFavorite'])->name('user.favorites.toggle');
        Route::post('/favorites/sync', [UserAccountController::class, 'syncFavorites'])->name('user.favorites.sync');

        Route::get('/recherche', [UserAccountController::class, 'searches'])->name('user.searches');
        Route::delete('/recherche/{id}', [UserAccountController::class, 'destroySearch'])->name('user.searches.delete');
    });
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::prefix('/services-petites-annonces')
        ->as('services-petites-annonces.')
        ->middleware('can:user.verifID')
        ->group(function () {
            Route::get('/', [ServicePetiteAnnonceController::class, 'index'])->name('index');
            Route::post('/v/{id}', [ServicePetiteAnnonceController::class, 'verify'])->name('verify');
            Route::post('/r/{id}', [ServicePetiteAnnonceController::class, 'reject'])->name('reject');
        });

    Route::prefix('/services')
        ->as('services.')
        ->middleware('can:annonce.status')
        ->group(function () {
            Route::get('/annonces', [AnnonceController::class, 'servicesShowStatus'])->name('annonces');
            Route::post('/annonces/{id}/toggle-sms', [AnnonceController::class, 'toggleSmsVerifie'])
                ->name('annonces.toggleSms')
                ->middleware('can:annonce.toggleSms');
        });

    Route::prefix('/services/immobilier')
        ->as('services.immobilier.')
        ->middleware('can:annonce.immobilier')
        ->group(function () {
            Route::get('/annonces', [AnnonceController::class, 'immobilierAnnonces'])->name('annonces');
            Route::post('/annonces/{id}/accepter', [AnnonceController::class, 'accepterAnnonce'])->name('accepter');
            Route::post('/annonces/{id}/rejeter', [AnnonceController::class, 'rejeterAnnonce'])->name('rejeter');
        });

    Route::prefix('/services/inscription')
        ->as('services.inscription.')
        ->middleware('can:service.inscription')
        ->group(function () {
            Route::get('/', [ServiceInscriptionController::class, 'index'])->name('index');
        });
});
