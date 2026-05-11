<?php

namespace App\Models;

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
        'estado', 'motivo_rechazo', 'enviado_por', 'ip_origen', 'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Múltiples imágenes
    public function imagenes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AporteImagen::class)->orderBy('orden');
    }

    /**
     * URL de la primera imagen (compatibilidad legado).
     */
    public function getImagenUrlAttribute(): ?string
    {
        return $this->img_path ? asset('storage/' . $this->img_path) : null;
    }

    public function comentarios(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comentario::class, 'tipo_id')
                    ->where('tipo', 'aporte')
                    ->orderByDesc('creado_en');
    }
}
