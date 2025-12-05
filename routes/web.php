<?php use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AnnonceController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\CNIController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [AnnonceController::class, 'index'])->name('home');
Route::get('/annonce/{id}', [AnnonceController::class, 'view'])->name('annonce.view');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.profile');

use App\Http\Controllers\ReservationController;

Route::get('/mes-reservations', [ReservationController::class, 'mesReservations'])
    ->middleware('auth')
    ->name('user.mesReservations'); 

Route::get('/connexion', [ConnexionController::class, 'showEmailForm'])
    ->middleware('guest')
    ->name('auth.check');

Route::post('/connexion', [ConnexionController::class, 'checkEmail'])
    ->middleware('guest')
    ->name('auth.verify');

Route::middleware([ 
    'auth:sanctum', 
    config('jetstream.auth_session'), 
    'verified', 
    ])->group(function () { 
        Route::get('/dashboard', function () { 
            $user = Auth::user();
            return view('dashboard', ['user' => $user]); 
        })->name('dashboard');

        Route::get('/deposer-une-annonce', [AnnonceController::class, 'create'])->name('annonce.create');
        Route::post('/annonce', [AnnonceController::class, 'store'])->name('annonce.store');

        Route::get('/cni', [CNIController::class, 'index'])->name('cni.index');
        Route::post('/cni', [CNIController::class, 'store'])->name('cni.store');
        Route::get('/compte/profil/modifier', [UserAccountController::class, 'edit'])->name('user.edit');
        Route::get('/compte/mes-annonces', [UserAccountController::class, 'annonces'])->name('user.annonces');
        Route::get('/compte/profil-espaces', [UserAccountController::class, 'spaces'])->name('user.spaces');
        Route::get('/compte/connexion-securite', [UserAccountController::class, 'security'])->name('user.security');
        Route::get('/compte/parametres', [UserAccountController::class, 'settings'])->name('user.settings');
        Route::post('/compte/parametres', [UserAccountController::class, 'updateSettings'])->name('user.settings.update');
    });