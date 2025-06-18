<?php

namespace App\Models;
use App\Enums\Especie;
use App\Enums\Sexo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Obtener la URL de la imagen de la mascota
     */
    public function getImagenUrlAttribute()
    {
        if ($this->imagen) {
            return asset('storage/' . $this->imagen);
        }
        return asset('storage/mascotas/default_pet.jpg');
    }

    /**
     * Obtener mascotas con cache
     */
    public static function getCachedMascotas($userId, $filters = [])
    {
        $cacheKey = "mascotas_user_{$userId}_" . md5(serialize($filters));
        
        return Cache::remember($cacheKey, 300, function () use ($userId, $filters) {
            $query = self::with(['usuario', 'historial' => function($q) {
                $q->latest()->limit(5);
            }, 'recordatorios' => function($q) {
                $q->where('realizado', false)->where('fecha', '>=', now()->toDateString());
            }])->where('user_id', $userId);
            
            // Aplicar filtros directamente en la consulta
            if (!empty($filters['busqueda'])) {
                $query->where('nombre', 'like', '%' . $filters['busqueda'] . '%');
            }
            
            if (!empty($filters['especie'])) {
                $query->where('especie', $filters['especie']);
            }
            
            if (!empty($filters['raza'])) {
                $query->where('raza', $filters['raza']);
            }
            
            if (!empty($filters['sexo'])) {
                $query->where('sexo', $filters['sexo']);
            }
            
            return $query->get();
        });
    }

    /**
     * Limpiar cache cuando se actualiza una mascota
     */
    protected static function booted()
    {
        static::saved(function ($mascota) {
            Cache::forget("mascotas_user_{$mascota->user_id}_*");
        });
        
        static::deleted(function ($mascota) {
            Cache::forget("mascotas_user_{$mascota->user_id}_*");
        });
    }
}
