<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class Recordatorio extends Model

{

    protected $dates = ['fecha'];
    protected $casts = [
        'fecha' => 'date',
    ];
    protected $fillable = [
        'titulo',
        'fecha',
        'descripcion',
        'realizado',
        'mascota_id', // opcional, por si lo usas de forma directa alguna vez
    ];
    public function mascota()
    {
        return $this->belongsTo(Mascota::class);
    }
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    public function getRouteKeyName()
    {
        return 'hashid';
    }

    public function getHashidAttribute()
    {
        return Hashids::encode($this->getKey());
    }

}
