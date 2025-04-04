<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialMedico extends Model
{
     protected $fillable = [
            'mascota_id', 'fecha', 'tipo', 'descripcion', 'veterinario'
        ];

        public function mascota()
        {
            return $this->belongsTo(Mascota::class);
        }
     protected $casts = [
            'fecha' => 'date', // ✅ Esto convierte automáticamente a Carbon
        ];
}
