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

    public function plantas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Planta::class);
    }
}