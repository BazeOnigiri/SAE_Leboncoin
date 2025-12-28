<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    protected $table = 'date';

    protected $primaryKey = 'iddate';

    public $timestamps = false;
    
    // Si la colonne contenant la date réelle s'appelle 'date' (recommandé)
    protected $fillable = ['date']; 
    
    // Cast la colonne date en objet Carbon
    protected $casts = [
        'date' => 'date',
    ]; 

    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'relier', 'iddate', 'idannonce')
                    ->withPivot('estdisponible');
    }

    public function particulier()
    {
        return $this->hasOne(Particulier::class, 'iddate', 'iddate');
    }
}