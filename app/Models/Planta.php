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
        'reino', 'division', 'clase', 'orden', 'familia', 'genero', 'taxonomia_nota', 'descripcion',
        'img_url', 'img_path', 'foto_referencia_url', 'verificada', 'tags',
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


    /**
     * Clasificación taxonómica completa, en el orden jerárquico estándar
     * (Reino → División → Clase → Orden → Familia → Género → Especie),
     * lista para recorrer en la vista. La Especie toma el valor de
     * `cientifico`. Los niveles sin dato quedan en null (se muestran
     * como "No determinado" en la vista).
     */
    public function getTaxonomiaAttribute(): array
    {
        return [
            'Reino'    => $this->reino,
            'División' => $this->division,
            'Clase'    => $this->clase,
            'Orden'    => $this->orden,
            'Familia'  => $this->familia,
            'Género'   => $this->genero,
            'Especie'  => $this->cientifico ?: null,
        ];
    }

    /**
     * True si tiene al menos un nivel taxonómico registrado (más allá
     * del Reino), es decir, si la identificación no quedó totalmente
     * indeterminada.
     */
    public function getTaxonomiaDeterminadaAttribute(): bool
    {
        return (bool) ($this->clase || $this->orden || $this->familia || $this->genero);
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
