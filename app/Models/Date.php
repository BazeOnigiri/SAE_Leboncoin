<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    protected $table = 'dates';

    protected $primaryKey = 'iddate';

    public $timestamps = false;

    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'relier', 'iddate', 'idannonce')
                    ->withPivot('estdisponible');
    }
}
