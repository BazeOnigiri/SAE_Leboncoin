<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CNIController;
use App\Models\Particulier;
use App\Models\Professionnel;
use App\Models\User;
use App\Models\Annonce;
use App\Models\Adresse;
use App\Models\Date as DateModel;
use App\Models\Heure;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DevController extends Controller
{
    public function loginAs(Request $request)
    {
        $email = $request->input('email');
        abort_if(!$email, 400);

        $user = User::where('email', $email)->first();
        abort_if(!$user, 404);

        if (!$user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended('/dashboard');
    }

    public function createAnnonce(Request $request)
    {
        $user = Auth::user();
        abort_if(!$user, 401);

        $adresseId = Adresse::min('idadresse');
        $dateId = DateModel::min('iddate');
        $typeId = TypeHebergement::min('idtypehebergement');

        abort_if(!$adresseId || !$dateId || !$typeId, 500, 'Seed data missing for adresse/date/typehebergement');

        $heureDepart = Heure::firstOrCreate(['heure' => '10:00'], ['heure' => '10:00']);
        $heureArrivee = Heure::firstOrCreate(['heure' => '18:00'], ['heure' => '18:00']);

        $annonce = Annonce::create([
            'idadresse' => $adresseId,
            'iddate' => $dateId,
            'idheuredepart' => $heureDepart->idheure,
            'idtypehebergement' => $typeId,
            'idheurearrivee' => $heureArrivee->idheure,
            'idutilisateur' => $user->idutilisateur,
            'titreannonce' => 'Dev annonce ' . Str::upper(Str::random(4)),
            'descriptionannonce' => 'Annonce créée via menu dev pour tests.',
            'prixnuitee' => random_int(40, 150),
            'minimumnuitee' => 1,
            'possibiliteanimaux' => false,
            'nombrebebesmax' => 0,
            'possibilitefumeur' => false,
            'capacite' => 2,
            'nbchambres' => 1,
            'montantacompte' => null,
            'pourcentageacompte' => null,
            'estverifie' => false,
        ]);

        return redirect()->intended('/compte/mes-annonces')->with('success', 'Annonce dev créée avec l\'ID #' . $annonce->idannonce);
    }

    public function createCni(Request $request)
    {
        $user = Auth::user();
        abort_if(!$user, 401);

        $urlRecto = 'https://images.squarespace-cdn.com/content/v1/600757141ab2dc6f4c35c661/1615406994382-RHRJR18HYTB6SNQQ15TB/MCLOVIN-SHADOW.png';
        $urlVerso = 'https://cosology.com/cdn/shop/products/BADGEMCLOVIN-back.png?v=1568345402&width=1426 1426w';

        $response = Http::timeout(10)->get($urlRecto);
        if ($response->successful()) {
            $rectoData = $response->body();
        }

        $response = Http::timeout(10)->get($urlVerso);
        if ($response->successful()) {
            $versoData = $response->body();
        }

        $rectoTmp = tmpfile();
        $versoTmp = tmpfile();
        fwrite($rectoTmp, $rectoData);
        fwrite($versoTmp, $versoData);

        $rectoPath = stream_get_meta_data($rectoTmp)['uri'];
        $versoPath = stream_get_meta_data($versoTmp)['uri'];

        $rectoFile = new UploadedFile($rectoPath, 'recto.png', 'image/png', null, true);
        $versoFile = new UploadedFile($versoPath, 'verso.png', 'image/png', null, true);

        $fakeRequest = Request::create(route('cni.store'), 'POST');
        $fakeRequest->files->set('recto', $rectoFile);
        $fakeRequest->files->set('verso', $versoFile);

        $fakeRequest->setUserResolver(fn () => $user);
        $fakeRequest->setLaravelSession($request->session());

        return app(CNIController::class)->store($fakeRequest);
    }

    public function createUser(Request $request)
    {
        $type = $request->input('type');
        abort_unless(in_array($type, ['particulier', 'professionnel']), 400);

        $email = 'dev_' . $type . '_' . Str::lower(Str::random(6)) . '@example.com';

        do {
            $phone = '06' . str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (User::where('telephoneutilisateur', $phone)->exists());

        $user = User::create([
            'pseudonyme' => Str::title($type) . ' ' . Str::random(4),
            'password' => bcrypt('password'),
            'email' => $email,
            'email_verified_at' => now(),
            'solde' => 0,
            'idadresse' => 1,
            'iddate' => 1,
            'telephoneutilisateur' => $phone,
        ]);

        if ($type === 'particulier') {
            Particulier::create([
                'iddate' => 1,
                'idutilisateur' => $user->idutilisateur,
                'nomutilisateur' => 'DevNom',
                'prenomutilisateur' => 'DevPrenom',
                'civilite' => 'Monsieur',
            ]);
        } else {
            $siret = '';
            for ($i = 0; $i < 13; $i++) {
                $siret .= random_int(0, 9);
            }

            Professionnel::create([
                'idutilisateur' => $user->idutilisateur,
                'numsiret' => $siret,
                'nomsociete' => 'DevSociete',
                'secteuractivite' => 'Informatique',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended('/dashboard');
    }
}
