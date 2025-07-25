<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialMedico extends Model
{
    use HasFactory;
     protected $fillable = [
            'mascota_id', 'fecha', 'tipo', 'descripcion', 'veterinario_id'
        ];

        public function mascota()
        {
            return $this->belongsTo(Mascota::class);
        }
     protected $casts = [
            'fecha' => 'date', // ✅ Esto convierte automáticamente a Carbon
        ];
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

