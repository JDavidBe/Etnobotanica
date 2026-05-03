<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    /** Devuelve las últimas 20 notificaciones del usuario autenticado (JSON). */
    public function index(Request $request)
    {
        $notifs = $request->user()->notificaciones()->limit(20)->get();
        $noLeidas = $notifs->where('leida', false)->count();

        return response()->json([
            'no_leidas'     => $noLeidas,
            'notificaciones'=> $notifs,
        ]);
    }

    /** Marca todas como leídas. */
    public function marcarLeidas(Request $request)
    {
        $request->user()->notificaciones()->where('leida', false)->update(['leida' => true]);
        return response()->json(['ok' => true]);
    }

    /** Marca una sola como leída. */
    public function marcarUna(Request $request, Notificacion $notificacion)
    {
        abort_if($notificacion->user_id !== $request->user()->id, 403);
        $notificacion->update(['leida' => true]);
        return response()->json(['ok' => true]);
    }
}
