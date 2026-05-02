<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\ComentarioLike;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tipo'      => 'required|in:planta,aporte',
            'tipo_id'   => 'required|integer',
            'contenido' => 'required|string|max:1000',
            'autor'     => 'nullable|string|max:80',
        ], [
            'contenido.required' => 'El comentario no puede estar vacío.',
            'contenido.max'      => 'El comentario no puede superar 1000 caracteres.',
        ]);

        $comentario = Comentario::create([
            'tipo'      => $request->tipo,
            'tipo_id'   => $request->tipo_id,
            'contenido' => $request->contenido,
            'autor'     => trim($request->autor) ?: 'Anónimo',
            'ip_origen' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'id'         => $comentario->id,
                'autor'      => $comentario->autor,
                'contenido'  => $comentario->contenido,
                'creado_en'  => $comentario->creado_en->format('d M Y, H:i'),
                'likes'      => 0,
                'ya_likeado' => false,
            ]);
        }

        return back()->with('success', '¡Comentario publicado!');
    }

    public function like(Request $request, Comentario $comentario)
    {
        $ip = $request->ip();

        $existing = ComentarioLike::where('comentario_id', $comentario->id)
            ->where('ip_origen', $ip)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            ComentarioLike::create([
                'comentario_id' => $comentario->id,
                'ip_origen'     => $ip,
            ]);
            $liked = true;
        }

        $total = $comentario->likes()->count();

        return response()->json(['likes' => $total, 'liked' => $liked]);
    }
}
