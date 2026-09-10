<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForoTema extends Model
{
    public const CATEGORIAS = [
        'general'      => 'General',
        'medicina'     => 'Medicina',
        'alimentacion' => 'Alimentación',
        'cultura'      => 'Cultura',
        'tecnico'      => 'Plataforma / Técnico',
    ];

    protected $fillable = ['titulo', 'contenido', 'user_id', 'categoria', 'cerrado', 'fijado', 'vistas'];

    protected $casts = [
        'cerrado' => 'boolean',
        'fijado'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respuestas()
    {
        return $this->hasMany(ForoRespuesta::class)->orderBy('created_at');
    }

    public function categoriaLabel(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? 'General';
    }
}