<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planta extends Model
{
    public $timestamps = false;

    const CREATED_AT  = 'creado_en';
    const UPDATED_AT  = 'actualizado_en';

    protected $fillable = [
        'nombre', 'cientifico', 'categoria_id', 'subtema_id',
        'uso', 'instrucciones', 'contexto', 'relato',
        'video_url', 'img_url', 'img_path', 'verificada', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'verificada' => 'boolean',
        ];
    }

    /**
     * Devuelve la URL pública de la imagen:
     * prioriza img_path (subida) sobre img_url (URL externa).
     */
    public function getImagenUrlAttribute(): ?string
    {
        if ($this->img_path) {
            return asset('storage/' . $this->img_path);
        }
        if ($this->img_url) {
            return $this->img_url;
        }
        return null;
    }

    public function categoria(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subtema(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subtema::class);
    }

    public function comentarios(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comentario::class, 'tipo_id')
                    ->where('tipo', 'planta')
                    ->orderByDesc('creado_en');
    }
}