<?php

namespace App\Models;
use App\Enums\Especie;
use App\Enums\Sexo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Mascota extends Model
{
     use HasFactory;
      protected $fillable = [
            'nombre', 'user_id', 'especie', 'raza', 'fecha_nacimiento', 'sexo', 'notas','imagen',
        ];


        protected $casts = [
            'especie' => Especie::class,
            'sexo' => Sexo::class,
             'fecha_nacimiento' => 'date',

        ];

        public function usuario()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function historial()
        {
            return $this->hasMany(HistorialMedico::class, 'mascota_id');
        }
    public function recordatorios()
    {
        return $this->hasMany(Recordatorio::class);
    }

}
