<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComentarioLike extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $fillable = ['comentario_id', 'ip_origen'];

    protected $casts = ['creado_en' => 'datetime'];

    public function comentario(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Comentario::class, 'comentario_id');
    }
}
