<?php

namespace App\Models;
use App\Enums\Especie;
use App\Enums\Sexo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Vinkla\Hashids\Facades\Hashids;

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
        $query = self::with(['usuario', 'historial' => function($q) {
            $q->latest()->limit(5);
        }, 'recordatorios' => function($q) {
            $q->where('realizado', false)->where('fecha', '>=', now()->toDateString());
        }])->where('user_id', $userId);

        // Aplica los filtros directamente en la consulta
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

        return $query; // Devuelve SIEMPRE el query builder, nunca cachees esto
    }

    /**
     * Limpiar cache cuando se actualiza una mascota
     */
    protected static function booted()
    {
        static::saved(function ($mascota) {
            self::clearUserMascotasCache($mascota->user_id);
        });
        
        static::deleted(function ($mascota) {
            self::clearUserMascotasCache($mascota->user_id);
        });
    }

    /**
     * Elimina todas las entradas de caché de mascotas para un usuario
     */
    protected static function clearUserMascotasCache($userId)
    {
        // Si el driver soporta tags, usar tags
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags('mascotas_user_' . $userId)->flush();
        } else {
            // Si no, buscar todas las claves manualmente solo si es Redis
            $cache = Cache::getStore();
            if (method_exists($cache, 'getRedis')) {
                $redis = $cache->getRedis();
                $prefix = $cache->getPrefix();
                $pattern = $prefix . "mascotas_user_{$userId}_*";
                foreach ($redis->keys($pattern) as $key) {
                    $redis->del($key);
                }
            }
            // Si es FileStore u otro, no se puede limpiar selectivamente
        }
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
    // Add this if you haven't already, for consistency and explicit retrieval
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field === 'hashid') {
            return $this->where('id', Hashids::decode($value))->firstOrFail();
        }
        return parent::resolveRouteBinding($value, $field);
    }
}
