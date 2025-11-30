<?php use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AnnonceController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConnexionController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [AnnonceController::class, 'index'])->name('home');
Route::get('/annonce/{id}', [AnnonceController::class, 'view'])->name('annonce.view');
Route::get('/user/{id}', [UserController::class, 'show'])->name('user.profile');


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
    });