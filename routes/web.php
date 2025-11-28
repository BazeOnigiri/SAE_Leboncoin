<?php use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AnnonceController; 
use App\Http\Controllers\ConnexionController;


Route::get('/', [AnnonceController::class, 'index'])->name('home');
Route::get('/annonce/{id}', [AnnonceController::class, 'view'])->name('annonce.view');


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
            return view('dashboard'); 
        })->name('dashboard'); 
    });