<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recherche extends Model
{
    use HasFactory;

    protected $table = 'recherche';
    protected $primaryKey = 'idrecherche';
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'idville',
        'iddepartement',
        'idregion',
        'iddatedebutrecherche',
        'iddatefinrecherche',
        'paiementenligne',
        'capaciteminimumvoyageur',
        'prixminimum',
        'prixmaximum',
        'nombreminimumchambre',
        'nombremaximumchambre',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idutilisateur');
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'idville');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'iddepartement');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'idregion');
    }

    public function dateDebut()
    {
        return $this->belongsTo(Date::class, 'iddatedebutrecherche');
    }

    public function dateFin()
    {
        return $this->belongsTo(Date::class, 'iddatefinrecherche');
    }

    public function typesHebergement()
    {
        return $this->belongsToMany(TypeHebergement::class, 'cibler', 'idrecherche', 'idtypehebergement');
    }

    public function commodites()
    {
        return $this->belongsToMany(Commodite::class, 'filtrer', 'idrecherche', 'idcommodite');
    }
}
