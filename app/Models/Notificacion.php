<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    public $timestamps = false;
    const CREATED_AT = 'creado_en';

    protected $fillable = ['user_id','tipo','titulo','mensaje','url','leida'];
    protected $casts    = ['leida' => 'boolean', 'creado_en' => 'datetime'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers de fábrica ────────────────────────────────────────
    public static function crearParaAdmins(string $tipo, string $titulo, string $mensaje, ?string $url = null): void
    {
        $admins = User::role(['admin','moderador'])->get();
        foreach ($admins as $u) {
            static::create(['user_id'=>$u->id,'tipo'=>$tipo,'titulo'=>$titulo,'mensaje'=>$mensaje,'url'=>$url]);
        }
    }

    public static function crearParaUser(int $userId, string $tipo, string $titulo, string $mensaje, ?string $url = null): void
    {
        static::create(['user_id'=>$userId,'tipo'=>$tipo,'titulo'=>$titulo,'mensaje'=>$mensaje,'url'=>$url]);
    }
}
