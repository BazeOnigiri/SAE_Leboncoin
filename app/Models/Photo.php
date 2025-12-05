<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photo';
    protected $primaryKey = 'idphoto';
    public $timestamps = false;

    protected $fillable = [
        'idannonce',
        'idincident',
        'lienphoto',
    ];

    /* Une photo a beaucoup ou pas de ... */

    public function utilisateurs()
    {
        return $this->hasMany(User::class, 'idphoto');
    }

    /* Une photo se réfere à 1 ... */

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'idannonce');
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'idincident');
    }
}
