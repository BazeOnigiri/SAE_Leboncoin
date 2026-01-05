<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incident';
    protected $primaryKey = 'idincident';
    public $timestamps = false;

    protected $fillable = [
        'idutilisateur',
        'idreservation',
        'motifincident',
        'descriptionincident',
        'iddate',
        'etape',
        'estclasse',
        'explicationproprietaire',
    ];

    public function compensationsDemandees()
    {
        return $this->belongsToMany(
            Compensation::class, 
            'demander',
            'idincident',
            'idcompensation'
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

    public function dateRecord()
    {
        return $this->belongsTo(Date::class, 'iddate', 'iddate');
    }
}