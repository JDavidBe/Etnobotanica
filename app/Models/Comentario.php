<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $fillable = [
        'tipo', 'tipo_id', 'contenido', 'autor', 'ip_origen', 'user_id',
    ];

    protected $casts = [
        'creado_en' => 'datetime',
    ];

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ComentarioLike::class, 'comentario_id');
    }

    public function totalLikes(): int
    {
        return $this->likes()->count();
    }

    public function yaLikeado(string $ip): bool
    {
        return $this->likes()->where('ip_origen', $ip)->exists();
    }
}
