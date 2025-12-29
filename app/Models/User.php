<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'utilisateur';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdminOrNoRoles() {
        return auth()->user()->roles->isEmpty() || auth()->user()->hasRole('Super Admin');
    }

    public function isCNIValidate()
    {
        return $this->identiteEstVerifie();
    }

    /* Un utilisateur a beaucoup ou pas de ... */

    public function annoncesPubliees()
    {
        return $this->hasMany(Annonce::class, 'idutilisateur');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'idutilisateur');
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'idutilisateur');
    }

    public function recherches()
    {
        return $this->hasMany(Recherche::class, 'idutilisateur');
    }

    public function cartesbancaires()
    {
        return $this->hasMany(CarteBancaire::class, 'idutilisateur');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'idutilisateur');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'idutilisateur');
    }

    public function particulier()
    {
        return $this->hasOne(Particulier::class, 'idutilisateur');
    }

    public function professionnels()
    {
        return $this->hasOne(Professionnel::class, 'idutilisateur');
    }


    public function adresse()
    {
        return $this->belongsTo(Adresse::class, 'idadresse');
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'idphoto');
    }

    public function cartebancaire()
    {
        return $this->belongsTo(Modele_referee::class, 'idcartebancaire');
    }

    /* Un utilisateur se réfere à beaucoup ou pas de ... */

    public function annoncesFavorisees()
    {
        return $this->belongsToMany(Annonce::class, 'favoriser', 'idutilisateur', 'idannonce');
    }

    public function defaultProfilePhotoUrl()
    {
        $name = $this->pseudonyme;

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) .
            '&color=7F9CF5&background=EBF4FF';
    }
    public function getNomDepartementAttribute()
    {
        return $this->adresse?->ville?->departement?->nomdepartement;
    }

    public function avisRecus()
    {
        return $this->hasManyThrough(
            Avis::class,       
            Annonce::class,   
            'idutilisateur', 
            'idannonce',       
            'idutilisateur',   
            'idannonce'       
        );
    }
    public function telephoneEstVerifie(){
        return (bool) $this->phone_verified;
    }
    public function identiteEstVerifie(){
        return (bool) $this->identity_verified;
    }
    
    public function hasCniFiles()
    {
        return is_dir(storage_path('app/private/cni/' . $this->idutilisateur));
    }

    public function estRecommande()
    {
        $bonneNote = ($this->avis_recus_avg_nombreetoiles ?? 0) >= 4;
        $assezAvis = ($this->avis_recus_count ?? 0) >= 10;
        $identiteOk = $this->identiteEstVerifie();
        $telephoneOk = $this->telephoneEstVerifie();
        return $bonneNote && $assezAvis && $identiteOk && $telephoneOk;
    }
    public function dateInscription()
    {
        return $this->belongsTo(\App\Models\Date::class, 'iddate', 'iddate');
    }
}
