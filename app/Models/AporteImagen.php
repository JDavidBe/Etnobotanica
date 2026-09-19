<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AporteImagen extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'creado_en';

    protected $table = 'aporte_imagenes';

    protected $fillable = ['aporte_id', 'img_path', 'orden'];

    protected $casts = ['creado_en' => 'datetime'];

    public function aporte(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Aporte::class);
    }

    public function getUrlAttribute(): string
    {
        return preg_match('#^(https?://|//)#i', $this->img_path)
            ? $this->img_path
            : asset('storage/' . $this->img_path);
    }
}
