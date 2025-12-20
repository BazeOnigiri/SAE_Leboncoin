<?php use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\AnnonceController; 
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\CNIController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\IncidentController;
use Illuminate\Http\Request;
use App\Models\User;

if (app()->environment('local')) {
    Route::post('/dev/login-as', function (Request $request) {
        $email = $request->input('email');

        abort_if(!$email, 400);

        $user = User::where('email', $email)->first();
        abort_if(!$user, 404);

        if (!$user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::logout();
        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended('/');
    })->name('dev.login-as');
}

Route::middleware(['auth'])->group(function () {
    // Si la clé de votre modèle Reservation est 'idreservation', la route doit être comme ceci :
    Route::get('/reservations/{reservation}/incident/signaler', [IncidentController::class, 'create'])
        ->name('incidents.create');

    Route::post('/reservations/{reservation}/incident', [IncidentController::class, 'store'])
        ->name('incidents.store');
});


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
        Route::get('/compte/mes-reservations', [ReservationController::class, 'mesReservations'])->name('user.mes-reservations');
        Route::get('/favorites', [UserAccountController::class, 'favorites'])->name('user.favorites');

        Route::prefix('services-petites-annonces')
        ->as('services-petites-annonces.')
        ->middleware('can:annonces.verif')
        ->group(function () {
            Route::get('/', [AnnonceController::class, 'annoncesAverifier'])->name('index');
            Route::post('/{id}', [AnnonceController::class, 'verifierAnnonce'])->name('verifier');
            Route::delete('/{id}', [AnnonceController::class, 'deleteAnnonce'])->name('delete');
        });
    });
    Route::get('/check-reservation/{id}', function ($id) {
        return redirect()->route('annonce.view', ['id' => $id]);
    })->middleware('auth')->name('check.reservation');

    Route::get('/reservation/creer/{id}', [ReservationController::class, 'create'])->name('reservation.create');