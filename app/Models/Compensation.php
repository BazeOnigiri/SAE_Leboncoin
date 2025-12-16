<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compensation extends Model
{
    use HasFactory;
    
    protected $table = 'compensation';
    protected $primaryKey = 'idcompensation';
    public $timestamps = false;

    // Relation avec les incidents qui demandent cette compensation
    public function incidents()
    {
        return $this->belongsToMany(
            Incident::class, 
            'demander', // Nom de la table pivot
            'idcompensation', // Clé étrangère locale dans la table pivot
            'idincident' // Clé étrangère liée dans la table pivot
        );
    }
}