<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'activo',
        'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'activo'            => 'boolean',
        ];
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function aportes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Aporte::class, 'user_id');
    }

    public function notificaciones(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notificacion::class, 'user_id')->orderByDesc('creado_en');
    }

    public function notificacionesNoLeidas(): int
    {
        return $this->notificaciones()->where('leida', false)->count();
    }
}