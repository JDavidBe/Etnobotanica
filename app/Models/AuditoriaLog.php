<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaLog extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'fecha';

    protected $table = 'auditoria_log';

    protected $fillable = ['accion', 'elemento', 'usuario_id'];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function usuario(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}