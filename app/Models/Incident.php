<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incident'; // Nom de la table
    protected $primaryKey = 'idincident'; // Nom de la clé primaire
    public $timestamps = false; // Pas de created_at/updated_at par défaut dans votre schéma

    // Champs que vous pouvez remplir massivement
    protected $fillable = [
        'idutilisateur',
        'idreservation',
        'motifincident',
        'descriptionincident',
        'iddate', // Assurez-vous d'avoir une logique pour cette colonne ou retirez-la
    ];

    // Relation avec les compensations demandées (Table 'demander')
    public function compensationsDemandees()
    {
        return $this->belongsToMany(
            Compensation::class, 
            'demander', // Nom de la table pivot
            'idincident', // Clé étrangère locale dans la table pivot
            'idcompensation' // Clé étrangère liée dans la table pivot
        );
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idutilisateur');
    }
}