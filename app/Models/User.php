<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

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

    public function particuliers()
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
}
