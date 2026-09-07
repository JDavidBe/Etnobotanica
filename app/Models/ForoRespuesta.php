<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForoRespuesta extends Model
{
    protected $fillable = ['foro_tema_id', 'user_id', 'contenido'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tema()
    {
        return $this->belongsTo(ForoTema::class, 'foro_tema_id');
    }
}