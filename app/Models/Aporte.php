<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Aporte extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $casts = [
        'creado_en' => 'datetime',
    ];
    protected $fillable = [
        'nombre_planta', 'cientifico', 'categoria',
        'uso', 'preparacion', 'relato',
        'img_path',
        'estado', 'enviado_por', 'ip_origen',
    ];

    /**
     * Devuelve la URL pública de la imagen del aporte.
     */
    public function getImagenUrlAttribute(): ?string
    {
        return $this->img_path ? asset('storage/' . $this->img_path) : null;
    }
}