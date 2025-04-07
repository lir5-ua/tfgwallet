<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

}
