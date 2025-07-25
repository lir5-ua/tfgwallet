<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Vinkla\Hashids\Facades\Hashids;

class Veterinario extends Authenticatable
{
    protected $fillable = [
        'nombre',
        'email',
        'numero_colegiado',
        'password',
        'telefono',
        'direccion',
    ];

    protected $hidden = [
        'password',
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

    public function resolveRouteBinding($value, $field = null)
    {
        $decodedId = Hashids::decode($value);
        if (empty($decodedId)) {
            return null;
        }
        return $this->where('id', $decodedId[0])->firstOrFail();
    }
}
