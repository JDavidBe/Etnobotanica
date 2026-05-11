<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $fillable = [
        'nombre',
        'icono',
        'descripcion',
        'orden',
    ];

    protected static function booted()
    {
        static::addGlobalScope('orden', function ($builder) {
            $builder->orderBy('orden')->orderBy('id');
        });
    }

    public function subtemas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subtema::class);
    }

    // Legado FK directo
    public function plantas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Planta::class);
    }

    // Muchos a muchos
    public function plantasM2M(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Planta::class, 'categoria_planta');
    }
}
