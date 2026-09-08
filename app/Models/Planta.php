<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planta extends Model
{
    public $timestamps = true;

    const CREATED_AT  = 'creado_en';
    const UPDATED_AT  = 'actualizado_en';

    protected $fillable = [
        'nombre', 'cientifico', 'categoria_id', 'subtema_id',
        'uso', 'instrucciones', 'contexto', 'relato',
        'video_url', 'video_persona_nombre', 'video_persona_rol', 'video_validado',
        'img_url', 'img_path', 'verificada', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'verificada'     => 'boolean',
            'video_validado' => 'boolean',
        ];
    }

    public function getImagenUrlAttribute(): ?string
    {
        if ($this->img_path) {
            return asset('storage/' . $this->img_path);
        }

        if (!$this->img_url) {
            return null;
        }

        if (preg_match('#^(https?://|//)#i', $this->img_url)) {
            return $this->img_url;
        }

        return asset($this->img_url);
    }

    // Categoría principal (FK legado)
    public function categoria(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // Muchos a muchos — todas las categorías de la planta
    public function categorias(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_planta');
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
