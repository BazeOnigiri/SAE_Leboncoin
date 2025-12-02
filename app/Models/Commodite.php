<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commodite extends Model
{
    protected $table = 'commodite';
    protected $primaryKey = 'idcommodite';
    public $timestamps = false;

    /**
     * Une commodité appartient à une catégorie.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'idcategorie');
    }

    /**
     * Une commodité peut être liée à plusieurs annonces.
     */
    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'proposer', 'idcommodite', 'idannonce');
    }
}