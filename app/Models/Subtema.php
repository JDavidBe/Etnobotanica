<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtema extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $fillable = ['categoria_id', 'nombre'];

    public function categoria(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function plantas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Planta::class);
    }
}