<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Vinkla\Hashids\Facades\Hashids;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'notificar_email',
        'is_admin',
        'password',
        'ultima_conexion',
        'silenciar_notificaciones_web',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notificar_email' => 'boolean',
            'ultima_conexion' => 'datetime',
            'is_admin' => 'boolean',
            'silenciar_notificaciones_web' => 'boolean',
        ];
    }
    public function mascotas()
    {
        return $this->hasMany(Mascota::class);
    }

    public function recordatorios()
    {
        return $this->hasManyThrough(Recordatorio::class, Mascota::class);
    }

    public function veterinarios()
    {
        return $this->belongsToMany(Veterinario::class, 'veterinario_user');
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
    public function resolveRouteBinding($value, $field = null)
    {
        $decodedId = Hashids::decode($value);

        if (empty($decodedId)) {
            return null;
        }

        return $this->where('id', $decodedId[0])->firstOrFail();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

}
