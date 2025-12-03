<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'idtransaction';
    public $timestamps = false;
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'idreservation', 'idreservation');
    }
    public function dateTransaction()
    {
        return $this->belongsTo(Date::class, 'iddate', 'iddate');
    }
    public function carteBancaire()
    {
        return $this->belongsTo(CarteBancaire::class, 'idcartebancaire', 'idcartebancaire');
    }
}